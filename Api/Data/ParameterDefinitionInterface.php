<?php

namespace Macopedia\Allegro\Api\Data;

use Macopedia\Allegro\Api\Data\ParameterDefinition\DictionaryItemInterface;
use Macopedia\Allegro\Api\Data\ParameterDefinition\RestrictionInterface;

interface ParameterDefinitionInterface
{
    const TYPE_INTEGER = 'integer';
    const TYPE_FLOAT = 'float';
    const TYPE_STRING = 'string';
    const TYPE_DICTIONARY = 'dictionary';

    const FRONTEND_TYPE_RANGE = 'range';
    const FRONTEND_TYPE_VALUES = 'values';
    const FRONTEND_TYPE_VALUES_IDS = 'values_ids';

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id);

    /**
     * @param string $label
     * @return void
     */
    public function setName(string $label);

    /**
     * @param string|null $unit
     * @return void
     */
    public function setUnit(?string $unit);

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type);

    /**
     * @param DictionaryItemInterface[] $dictionary
     * @return void
     */
    public function setDictionary(array $dictionary);

    /**
     * @param bool $required
     * @return void
     */
    public function setRequired(bool $required);

    /**
     * @param RestrictionInterface[] $restrictions
     * @return void
     */
    public function setRestrictions(array $restrictions);

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * @return string|null
     */
    public function getUnit(): ?string;

    /**
     * @return bool|null
     */
    public function getRequired(): ?bool;

    /**
     * @return DictionaryItemInterface[]
     */
    public function getDictionary(): array;

    /**
     * @return string|null
     */
    public function getFrontendType(): ?string;

    /**
     * @return RestrictionInterface[]
     */
    public function getRestrictions(): array;

    /**
     * @param string $type
     * @return bool
     */
    public function hasRestriction(string $type): bool;

    /**
     * @param string $type
     * @return mixed
     */
    public function getRestrictionValue(string $type): mixed;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
