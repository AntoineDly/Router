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

use Psr\Http\Message\StreamInterface;

final class Stream implements StreamInterface
{
    public function __construct(
        private mixed $stream,
        private ?int $size = null,
        private bool $isSeekable = false,
        private bool $isWritable = false,
        private bool $isReadable = false,
    ) {
    }

    
    public function __toString(): string
    {
        return $this->getContents();
    }

    
    public function close(): void
    {
        if (!isset($this->stream)) {
            return;
        }

        if (is_resource($this->stream)) {
            fclose($this->stream);
        }
        $this->detach();
    }

    
    public function detach()
    {
        if (!isset($this->stream)) {
            return null;
        }

        if (!is_resource($this->stream)) {
            return null;
        }

        $result = $this->stream;
        unset($this->stream);
        $this->size = null;
        $this->isSeekable = $this->isWritable = $this->isReadable = false;

        return $result;
    }

    
    public function getSize(): ?int
    {
        return $this->size;
    }

    
    public function tell(): int
    {
        if (!is_resource($this->stream)) {
            return 0;
        }

        return ftell($this->stream) ?: 0;
    }

    
    public function eof(): bool
    {
        if (!is_resource($this->stream)) {
            return false;
        }

        return feof($this->stream);
    }

    
    public function isSeekable(): bool
    {
        return $this->isSeekable;
    }

    
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!is_resource($this->stream) || !$this->isSeekable()) {
            return;
        }

        fseek($this->stream, $offset, $whence);
    }

    
    public function rewind(): void
    {
        $this->seek(offset: 0);
    }

    
    public function isWritable(): bool
    {
        return $this->isWritable;
    }

    /**
     * @inheritDoc
     * @return int<0, max>
     */
    public function write(string $string): int
    {
        if (!is_resource($this->stream) || !$this->isWritable()) {
            return 0;
        }

        return fwrite($this->stream, $string) ?: 0;
    }

    
    public function isReadable(): bool
    {
        return $this->isReadable;
    }

    /**
     * @inheritDoc
     * @param int<0, max> $length
     */
    public function read(int $length): string
    {
        if (!is_resource($this->stream) || !$this->isReadable()) {
            return '';
        }

        return fread($this->stream, $length) ?: '';
    }

    
    public function getContents(): string
    {
        if (!is_resource($this->stream)) {
            return '';
        }

        return stream_get_contents($this->stream) ?: '';
    }

    
    public function getMetadata(?string $key = null)
    {
        if (!is_resource($this->stream)) {
            return null;
        }


        $meta = stream_get_meta_data($this->stream);

        return $meta[$key] ?? null;
    }
}
