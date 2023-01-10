<?php

namespace Macopedia\Allegro\Api\Data\ParameterDefinition;

interface DictionaryItemInterface
{
    /**
     * @param string $value
     * @return void
     */
    public function setValue(string $value);

    /**
     * @param string $label
     * @return void
     */
    public function setLabel(string $label);

    /**
     * @return string|null
     */
    public function getValue(): ?string;

    /**
     * @return string|null
     */
    public function getLabel(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
