<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/*
 * This file is part of the AntoineDly/Router package.
 *
 * (c) Antoine Delaunay <antoine.delaunay333@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AntoineDly\Router;

use AntoineDly\Router\Attributes\BaseRoute;
use AntoineDly\Router\Attributes\ControllerRoute;
use AntoineDly\Router\Exceptions\RouteNotFoundException;
use AntoineDly\Router\Helpers\PathHelper;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use ReflectionAttribute;
use ReflectionMethod;

final class Router
{
    /**
     * @param Route[] $routes
     */
    public function __construct(
        private readonly ContainerInterface $container,
        private array $routes = []
    ) {
    }

    /** @param String[] $controllersName */
    public function registerRoutesFromControllerAttributes(array $controllersName): void
    {
        foreach ($controllersName as $controllerName) {
            /** @var ControllerInterface $controller */
            $controller = $this->container->get($controllerName);
            $reflectionController = new \ReflectionClass($controller);

            $controllerRoute = $reflectionController->getAttributes(
                name: ControllerRoute::class,
                flags: ReflectionAttribute::IS_INSTANCEOF
            );
            $baseRoutePath = $this->getBaseRoutePath($controllerRoute);

            foreach ($reflectionController->getMethods() as $method) {
                $attributes = $method->getAttributes(BaseRoute::class, ReflectionAttribute::IS_INSTANCEOF);
                foreach ($attributes as $attribute) {
                    $this->addRoute($attribute, $baseRoutePath, $controllerName, $method);
                }
            }
        }
    }

    public function resolve(RequestInterface $request): ResponseInterface
    {
        $action = $this->getAction($request);
        $className = $action->getControllerClass();
        $methodName = $action->getControllerMethod();

        if (!class_exists($className)) {
            throw new RouteNotFoundException('404 Route not found, class ' . $className . 'doesn\'t exist');
        }
        /** @var ControllerInterface $class */
        $class = $this->container->get($className);

        if (!(method_exists($class, $methodName) &&
            is_callable([$class, $methodName], true, $callable))) {
            throw new RouteNotFoundException('404 Route not found, method ' . $methodName . 'doesn\'t exist');
        }
        $response = (new ReflectionMethod($class, $methodName))->invoke($class, $request);

        if (!is_object($response)) {
            throw new Exception('It should return a Response Interface but return instead not an object : ' . $response);
        }
        if (!$response instanceof ResponseInterface) {
            throw new Exception('It should return a Response Interface but return instead : ' . get_class($response));
        }

        return $response;
    }

    private function getAction(RequestInterface $request): Route
    {
        $reqUri = explode('/', $request->getUri()->getPath());

        $action = null;
        foreach ($this->routes as $route) {
            if ($route->getMethod() !== $request->getMethod() || count($reqUri) !== count($route->getUriParamsAnSegments())) {
                continue;
            }
            if ($this->assertSegmentsArePresentInRequest($route->getUriSegments(), $reqUri)) {
                $action = $route;
                foreach ($route->getUriParams() as $key => $index) {
                    $request->withAddedHeader(substr($index, 1, -1), $reqUri[$key]);
                }
                break;
            }
        }

        if (is_null($action)) {
            throw new RouteNotFoundException('404 Route not found, action doesn\'t exist');
        }

        return $action;
    }

    /**
     * @param array<int, string> $keySegments
     * @param array<int, string> $reqUri
     */
    private function assertSegmentsArePresentInRequest(array $keySegments, array $reqUri): bool
    {
        foreach ($keySegments as $index => $segment) {
            if($reqUri[$index] !== $segment) {
                return false;
            }
        }
        return true;
    }

    private function register(Route $route): void
    {
        $this->routes[] = $route;
    }

    private function addRoute(
        ReflectionAttribute $attribute,
        string $baseRoutePath,
        string $controllerName,
        ReflectionMethod $method
    ): void {
        $route = $attribute->newInstance();
        if (!$route instanceof BaseRoute) {
            return;
        }

        $routeUri = $this->getRouteUri($route, $baseRoutePath);
        $uriParams = $uriSegments = [];

        foreach ($routeUri as $index => $param) {
            if (preg_match('/{.*}/', $param)) {
                $uriParams[$index] = $param;
            } else {
                $uriSegments[$index] = $param;
            }
        }

        $this->register(new Route(
            method: $route->method->value,
            controllerClass: $controllerName,
            controllerMethod: $method->getName(),
            uriParams: $uriParams,
            uriSegments: $uriSegments
        ));
    }

    /** @param ReflectionAttribute[] $controllerRoute */
    public function getBaseRoutePath(array $controllerRoute): string
    {
        if (!array_key_exists(0, $controllerRoute)) {
            return '';
        }
        $route = $controllerRoute[0]->newInstance();
        return $route instanceof ControllerRoute ? $route->routePath : '';
    }

    /**
     * @param BaseRoute $route
     * @param string $baseRoutePath
     * @return string[]
     */
    private function getRouteUri(BaseRoute $route, string $baseRoutePath): array
    {
        $routePath = PathHelper::formatPath(
            path: $route->standalone ? $route->routePath : $baseRoutePath . $route->routePath
        );
        return explode('/', $routePath);
    }
}
