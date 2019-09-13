<?php

namespace Macopedia\Allegro\Api\Data;

interface CategoryInterface
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
     * @param bool $leaf
     * @return void
     */
    public function setLeaf(bool $leaf);

    /**
     * @param string $parent
     * @return void
     */
    public function setParent(string $parent);

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @return bool
     */
    public function getLeaf(): bool;

    /**
     * @return string
     */
    public function getParent(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
