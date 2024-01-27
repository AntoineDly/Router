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

namespace AntoineDly\Router\Helpers;

use AntoineDly\Router\Uri;

final class UriHelper
{
    public static function createFrom(string $url): Uri
    {
        $components = parse_url($url);
        $scheme = $userInfo = $host = $path = $query = $fragment = '';
        $port = null;
        if (is_array($components)) {
            if (isset($components['scheme'])) {
                $scheme = $components['scheme'];
            }
            if (isset($components['user'])) {
                $userInfo = !isset($components['pass']) ? $components['user'] : $components['user'] .':'.$components['pass'];
            }
            if (isset($components['host'])) {
                $host = $components['host'];
            }
            if (isset($components['port'])) {
                $port = $components['port'];
            }
            if (isset($components['path'])) {
                $path = $components['path'];
            }
            if (isset($components['query'])) {
                $query = $components['query'];
            }
            if (isset($components['fragment'])) {
                $fragment = $components['fragment'];
            }
        }

        return new Uri(
            scheme: $scheme,
            userInfo: $userInfo,
            host: $host,
            port: $port,
            path: $path,
            query: $query,
            fragment: $fragment
        );
    }
}