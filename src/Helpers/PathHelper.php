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

final class PathHelper
{
    public static function formatPath(string $path): string
    {
        if ($path === '/index.php') {
            return '';
        }

        return preg_replace('/(^\/)|(\/$)/', '', $path) ?? '';
    }
}
