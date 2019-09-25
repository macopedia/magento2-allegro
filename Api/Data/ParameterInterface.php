<?php

namespace Macopedia\Allegro\Api\Data;

interface ParameterInterface
{

    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id);

    /**
     * @return int
     */
    public function getId(): ?int;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);

    /**
     * @return array
     */
    public function getRawData(): array;

    /**
     * @return bool
     */
    public function isValueEmpty(): bool;
}
