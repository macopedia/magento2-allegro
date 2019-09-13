<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\BuyerInterface;
use Magento\Framework\DataObject;

class Buyer extends DataObject implements BuyerInterface
{

    const FIRST_NAME_FIELD_NAME = 'first_name';
    const LAST_NAME_FIELD_NAME = 'last_name';
    const EMAIL_FIELD_NAME = 'email';

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName)
    {
        $this->setData(self::FIRST_NAME_FIELD_NAME, $firstName);
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName)
    {
        $this->setData(self::LAST_NAME_FIELD_NAME, $lastName);
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->setData(self::EMAIL_FIELD_NAME, $email);
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->getData(self::FIRST_NAME_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->getData(self::LAST_NAME_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->getData(self::EMAIL_FIELD_NAME);
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['firstName'])) {
            $this->setFirstName($rawData['firstName']);
        }
        if (isset($rawData['lastName'])) {
            $this->setLastName($rawData['lastName']);
        }
        if (isset($rawData['email'])) {
            $this->setEmail($rawData['email']);
        }
    }
}
