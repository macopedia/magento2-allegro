<?php

namespace Macopedia\Allegro\Api\Data;

interface ImageInterface
{
    const STATUS_LOCAL = 0;
    const STATUS_UPLOADED = 1;

    /**
     * @param string $url
     * @return void
     */
    public function setUrl(string $url);

    /**
     * @param int $status
     * @return void
     */
    public function setStatus(int $status);

    /**
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * @return int|null
     */
    public function getStatus(): ?int;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);

    /**
     * @return array
     */
    public function getRawData(): array;
}
