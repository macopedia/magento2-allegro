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
     * @param int $stats
     * @return void
     */
    public function setStatus(int $stats);

    /**
     * @return string
     */
    public function getUrl(): ?string;

    /**
     * @return string
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
