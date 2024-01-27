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

namespace AntoineDly\Router\Attributes;

use AntoineDly\Router\HttpMethodEnum;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
readonly class BaseRoute implements RouteInterface
{
    public function __construct(public string $routePath, public HttpMethodEnum $method = HttpMethodEnum::GET, public bool $standalone = false)
    {
    }
}
