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

use Psr\Http\Message\UriInterface;

final class Uri implements UriInterface
{
    public function __construct(
        private string $scheme,
        private string $userInfo,
        private string $host,
        private ?int $port,
        private string $path,
        private string $query,
        private string $fragment,
    ) {
    }
    
    public function getScheme(): string
    {
        return mb_strtolower($this->scheme);
    }

    public function getAuthority(): string
    {
        if ($this->userInfo !== '') {
            $authority = $this->userInfo.'@'.$this->host;
        } else {
            $authority = $this->host;
        }

        if ($this->port !== null) {
            $authority .= ':'.$this->port;
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    
    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }
    
    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme(string $scheme): UriInterface
    {
        $newUri = clone $this;
        $newUri->scheme = $scheme;
        return $newUri;
    }

    public function withUserInfo(string $user, ?string $password = null): UriInterface
    {
        $newUri = clone $this;
        $newUri->userInfo = is_null($password) ? $user : $user.':'.$password;
        return $newUri;
    }
    
    public function withHost(string $host): UriInterface
    {
        $newUri = clone $this;
        $newUri->host = $host;
        return $newUri;
    }
    
    public function withPort(?int $port): UriInterface
    {
        $newUri = clone $this;
        $newUri->port = $port;
        return $newUri;
    }
    
    public function withPath(string $path): UriInterface
    {
        $newUri = clone $this;
        $newUri->path = $path;
        return $newUri;
    }

    public function withQuery(string $query): UriInterface
    {
        $newUri = clone $this;
        $newUri->query = $query;
        return $newUri;
    }

    public function withFragment(string $fragment): UriInterface
    {
        $newUri = clone $this;
        $newUri->fragment = $fragment;
        return $newUri;
    }

    public function __toString(): string
    {
        $uri = '';

        if ($this->getScheme() !== '') {
            $uri .= $this->getScheme().':';
        }

        if ($this->getAuthority() !== '' || $this->getScheme() === 'file') {
            $uri .= '//'.$this->getAuthority();
        }

        if ($this->getAuthority() !== '' && $this->getPath() !== '') {
            $uri .= '/'.$this->getPath();
        } else {
            $uri .= $this->getPath();
        }

        if ($this->getQuery() !== '') {
            $uri .= '?'.$this->getQuery();
        }

        if ($this->getFragment() !== '') {
            $uri .= '#'.$this->getFragment();
        }

        return $uri;
    }
}
