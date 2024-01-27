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
use Exception;
use Psr\Http\Message\ResponseInterface;
use TypeError;

final class JsonResponse extends Response implements ResponseInterface
{
    protected string $data = '';
    public const DEFAULT_ENCODING_OPTIONS = 15;

    public function __construct(
        mixed $data,
        int $statusCode = 200,
        bool $json = false,
        private int $encodingOptions = self::DEFAULT_ENCODING_OPTIONS
    ) {
        parent::__construct(null, $statusCode);
        if ($json && !is_string($data) && !is_callable([$data, '__toString'])) {
            throw new TypeError(sprintf('"%s": If $json is set to true, argument $data must be a string or object implementing __toString(), "%s" given.', __METHOD__, get_debug_type($data)));
        }
        $json ? $this->setJson($data) : $this->setData($data);
    }

    public function getEncodingOptions(): int
    {
        return $this->encodingOptions;
    }

    public function setData(mixed $data): Response
    {
        $data = json_encode($data, $this->getEncodingOptions());
        if (!$data) {
            throw new Exception('$data shouldn\'t return false but a string');
        }
        return $this->setJson($data);
    }

    public function setJson(mixed $json): Response
    {
        if (is_callable([$json, '__toString'])) {
            $json = $json::__toString();
        }
        $this->data = $json;
        return $this->update();
    }

    private function update(): Response
    {
        return $this->withBody(StreamHelper::createFrom($this->data));
    }
}
