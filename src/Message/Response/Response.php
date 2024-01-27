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

namespace AntoineDly\Router\Message\Response;

use AntoineDly\Router\Helpers\StreamHelper;
use AntoineDly\Router\Message\MessageTrait;
use Psr\Http\Message\ResponseInterface;

abstract class Response implements ResponseInterface
{
    use MessageTrait;

    protected string $reasonPhrase;

    public function __construct(
        ?string $body,
        protected int $statusCode = 200,
    ) {
        $this->setBody(StreamHelper::createFrom($body));
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus(int $code, string $reasonPhrase = ''): ResponseInterface
    {
        $this->statusCode = $code;
        $this->reasonPhrase = $reasonPhrase;
        return $this;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function send(): void
    {
        echo $this->getBody()->getContents();
    }
}
