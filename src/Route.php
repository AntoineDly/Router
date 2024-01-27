<?php

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

final readonly class Route
{
    /**
     * @param array<int, string> $uriParams
     * @param array<int, string> $uriSegments
     */
    public function __construct(
        private string $method,
        private string $controllerClass,
        private string $controllerMethod,
        private array $uriParams,
        private array $uriSegments
    ) {
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getControllerClass(): string
    {
        return $this->controllerClass;
    }

    public function getControllerMethod(): string
    {
        return $this->controllerMethod;
    }

    /** @return array<int, string> */
    public function getUriParams(): array
    {
        return $this->uriParams;
    }

    /** @return array<int, string> */
    public function getUriSegments(): array
    {
        return $this->uriSegments;
    }

    /** @return array<int, string> */
    public function getUriParamsAnSegments(): array
    {
        return array_merge($this->uriParams, $this->uriSegments);
    }
}
