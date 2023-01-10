<?php


namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\ParameterDefinition\DictionaryItemInterface;
use Macopedia\Allegro\Api\Data\ParameterDefinition\DictionaryItemInterfaceFactory;
use Macopedia\Allegro\Api\Data\ParameterDefinition\RestrictionInterface;
use Macopedia\Allegro\Api\Data\ParameterDefinition\RestrictionInterfaceFactory;
use Macopedia\Allegro\Api\Data\ParameterDefinitionInterface;
use Magento\Framework\DataObject;

class ParameterDefinition extends DataObject implements ParameterDefinitionInterface
{

    const ID_FIELD_NAME = 'id';
    const NAME_FIELD_NAME = 'name';
    const UNIT_FIELD_NAME = 'unit';
    const REQUIRED_FIELD_NAME = 'required';
    const VALUES_COUNT_FIELD_NAME = 'values_count';
    const TYPE_FIELD_NAME = 'type';
    const DICTIONARY_FIELD_NAME = 'dictionary';
    const RESTRICTIONS_FIELD_NAME = 'restrictions';

    /** @var DictionaryItemInterfaceFactory */
    private $dictionaryItemFactory;

    /** @var RestrictionInterfaceFactory */
    private $restrictionFactory;

    /**
     * ParameterDefinition constructor.
     * @param DictionaryItemInterfaceFactory $dictionaryItemFactory
     * @param RestrictionInterfaceFactory $restrictionFactory
     */
    public function __construct(
        DictionaryItemInterfaceFactory $dictionaryItemFactory,
        RestrictionInterfaceFactory $restrictionFactory
    ) {
        $this->dictionaryItemFactory = $dictionaryItemFactory;
        $this->restrictionFactory = $restrictionFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function setId(string $id)
    {
        $this->setData(self::ID_FIELD_NAME, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function setName(string $label)
    {
        $this->setData(self::NAME_FIELD_NAME, $label);
    }

    /**
     * {@inheritDoc}
     */
    public function setType(string $type)
    {
        $this->setData(self::TYPE_FIELD_NAME, $type);
    }

    /**
     * {@inheritDoc}
     */
    public function setUnit(?string $type)
    {
        $this->setData(self::UNIT_FIELD_NAME, $type);
    }

    /**
     * {@inheritDoc}
     */
    public function setRequired(bool $required)
    {
        $this->setData(self::REQUIRED_FIELD_NAME, $required);
    }

    /**
     * {@inheritDoc}
     */
    public function setDictionary(array $dictionary)
    {
        $this->setData(self::DICTIONARY_FIELD_NAME, $dictionary);
    }

    /**
     * {@inheritDoc}
     */
    public function setRestrictions(array $restrictions)
    {
        $this->setData(self::RESTRICTIONS_FIELD_NAME, $restrictions);
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): ?string
    {
        return $this->getData(self::ID_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): ?string
    {
        return $this->getData(self::TYPE_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getUnit(): ?string
    {
        return $this->getData(self::UNIT_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getRequired(): bool
    {
        return $this->getData(self::REQUIRED_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getDictionary(): array
    {
        return $this->getData(self::DICTIONARY_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getRestrictions(): array
    {
        return $this->getData(self::RESTRICTIONS_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function hasRestriction(string $type): bool
    {
        foreach ($this->getRestrictions() as $restriction) {
            if ($restriction->getType() == $type) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getRestrictionValue(string $type): mixed
    {
        foreach ($this->getRestrictions() as $restriction) {
            if ($restriction->getType() == $type) {
                return $restriction->getValue();
            }
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getFrontendType(): string
    {
        if ($this->getType() == self::TYPE_DICTIONARY) {
            return self::FRONTEND_TYPE_VALUES_IDS;
        }

        if ($this->hasRestriction('range') && $this->getRestrictionValue('range') === true) {
            return self::FRONTEND_TYPE_RANGE;
        }

        return self::FRONTEND_TYPE_VALUES;
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
        }
        if (isset($rawData['name'])) {
            $this->setName($rawData['name']);
        }
        if (isset($rawData['type'])) {
            $this->setType($rawData['type']);
        }
        if (isset($rawData['unit'])) {
            $this->setUnit($rawData['unit']);
        }

        $this->setRequired($rawData['required'] ?? false);
        $this->setRestrictions($this->mapRestrictionsData($rawData['restrictions'] ?? []));
        $this->setDictionary($this->mapDictionaryData($rawData['dictionary'] ?? []));
    }

    /**
     * @param array $data
     * @return DictionaryItemInterface[]
     */
    private function mapDictionaryData(array $data): array
    {
        $dictionary = [];
        foreach ($data as $dictionaryItemData) {
            /** @var DictionaryItemInterface $dictionaryItem */
            $dictionaryItem = $this->dictionaryItemFactory->create();
            $dictionaryItem->setRawData($dictionaryItemData);
            $dictionary[] = $dictionaryItem;
        }
        return $dictionary;
    }

    /**
     * @param array $data
     * @return RestrictionInterface[]
     */
    private function mapRestrictionsData(array $data): array
    {
        $restrictions = [];
        foreach ($data as $type => $value) {
            /** @var RestrictionInterface $restriction */
            $restriction = $this->restrictionFactory->create();
            $restriction->setType($type);
            $restriction->setValue($value);
            $restrictions[] = $restriction;
        }
        return $restrictions;
    }
}
