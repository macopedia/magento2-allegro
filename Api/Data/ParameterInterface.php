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
     * @param $value
     * @return void
     */
    public function setValue($value);

    /**
     * @return int
     */
    public function getId(): ?int;

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
