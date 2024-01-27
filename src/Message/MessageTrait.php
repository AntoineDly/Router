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

namespace AntoineDly\Router\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;

trait MessageTrait
{
    /** @var string[][] $headers */
    private array $headers;
    private string $version = '1.0';
    private StreamInterface $body;
    
    /** @param string[][] $headers */
    private function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }
    
    public function getHeaders(): array
    {
        return $this->headers;
    }
    
    public function getHeader(string $headerName): array
    {
        if (!$this->hasHeader($headerName)) {

        }

        return $this->headers[$headerName];
    }

    public function getHeaderLine(string $headerName): string
    {
        return implode(', ', $this->getHeader($headerName));
    }
    
    public function hasHeader(string $headerName): bool
    {
        return in_array($headerName, array_keys($this->headers));
    }

    public function withHeader(string $name, $value): self
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $newRequest = clone $this;
        $newRequest->headers = [$name => $value];
        return $newRequest;
    }
    
    public function withAddedHeader(string $name, $value): self
    {
        if (!is_array($value)) {
            $value = [$value];
        }
        $newRequest = clone $this;
        $headers = $this->headers;
        $headers[$name] = $value;
        $newRequest->headers = $headers;
        return $newRequest;
    }
    
    public function withoutHeader(string $name): self
    {
        unset($this->headers[$name]);
        return $this;
    }
    
    public function getProtocolVersion(): string
    {
        return $this->version;
    }
    
    public function withProtocolVersion(string $version): self
    {
        $newRequest = clone $this;
        $newRequest->version = $version;
        return $newRequest;
    }

    private function setBody(StreamInterface $body): self
    {
        $this->body = $body;
        return $this;
    }

    
    public function getBody(): StreamInterface
    {
        return $this->body;
    }
    
    public function withBody(StreamInterface $body): self
    {
        $newRequest = clone $this;
        $newRequest->body = $body;
        return $newRequest;
    }
}
