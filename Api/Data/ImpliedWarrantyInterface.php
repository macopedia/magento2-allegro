<?php

namespace Macopedia\Allegro\Api\Data;

interface ImpliedWarrantyInterface
{

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id);

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name);

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
