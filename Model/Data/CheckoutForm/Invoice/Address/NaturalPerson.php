<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm\Invoice\Address;

use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address\NaturalPersonInterface;
use Magento\Framework\DataObject;

class NaturalPerson extends DataObject implements NaturalPersonInterface
{

    const FIRST_NAME_FIELD_NAME = 'first_name';
    const LAST_NAME_FIELD_NAME = 'last_name';

    /**
     * {@inheritDoc}
     */
    public function setFirstName(string $firstName)
    {
        $this->setData(self::FIRST_NAME_FIELD_NAME, $firstName);
    }

    /**
     * {@inheritDoc}
     */
    public function setLastName(string $lastName)
    {
        $this->setData(self::LAST_NAME_FIELD_NAME, $lastName);
    }

    /**
     * {@inheritDoc}
     */
    public function getFirstName(): ?string
    {
        return $this->getData(self::FIRST_NAME_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getLastName(): ?string
    {
        return $this->getData(self::LAST_NAME_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['firstName'])) {
            $this->setFirstName($rawData['firstName']);
        }
        if (isset($rawData['lastName'])) {
            $this->setLastName($rawData['lastName']);
        }
    }
}
