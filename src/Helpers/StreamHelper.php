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

use AntoineDly\Router\Stream;
use Psr\Http\Message\StreamInterface;

final class StreamHelper
{
    public static function createFrom(
        mixed $stream,
        ?int $size = null,
        bool $isSeekable = false,
        bool $isWritable = false,
        bool $isReadable = false
    ): StreamInterface {
        return new Stream(
            stream: $stream,
            size: $size,
            isSeekable: $isSeekable,
            isWritable: $isWritable,
            isReadable: $isReadable
        );
    }
}
