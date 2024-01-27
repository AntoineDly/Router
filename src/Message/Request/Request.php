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

namespace AntoineDly\Router\Message\Request;

use AntoineDly\Router\Helpers\UriHelper;
use AntoineDly\Router\Message\MessageTrait;
use AntoineDly\Router\Stream;
use AntoineDly\Router\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

final class Request implements RequestInterface
{
    use MessageTrait;

    private string $method;
    private UriInterface $uri;
    private string $requestTarget;

    public function __construct(
    ) {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = UriHelper::createFrom(url: "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        $this
            ->setHeaders([
                'server' => $_SERVER,
                'request' => $_REQUEST,
                'session' => $_SESSION,
                'files' => $_FILES,
                'env' => $_ENV
            ])
            ->setBody(new Stream(file_get_contents('php://input')))
        ;
    }
    
    public function getMethod(): string
    {
        return $this->method;
    }
    
    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    public function withRequestTarget(string $requestTarget): RequestInterface
    {
        $newRequest = clone $this;
        $newRequest->requestTarget = $requestTarget;
        return $newRequest;
    }
    
    public function withMethod(string $method): RequestInterface
    {
        $newRequest = clone $this;
        $newRequest->method = $method;
        return $newRequest;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }
    
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $newRequest = clone $this;

        if ($preserveHost) {
            $newUri = $uri->withHost($this->getUri()->getHost());
            $newRequest->uri = $newUri;
        } else {
            $newRequest->uri = $uri;
        }

        return $newRequest;
    }
}
