<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\BuyerInterface;
use Magento\Framework\DataObject;

class Buyer extends DataObject implements BuyerInterface
{

    const FIRST_NAME_FIELD_NAME = 'first_name';
    const LAST_NAME_FIELD_NAME = 'last_name';
    const EMAIL_FIELD_NAME = 'email';
    const LOGIN_FIELD_NAME = 'login';

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
    public function setEmail(string $email)
    {
        $this->setData(self::EMAIL_FIELD_NAME, $email);
    }

    /**
     * {@inheritDoc}
     */
    public function setLogin(string $login)
    {
        $this->setData(self::LOGIN_FIELD_NAME, $login);
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
    public function getEmail(): ?string
    {
        return $this->getData(self::EMAIL_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getLogin(): ?string
    {
        return $this->getData(self::LOGIN_FIELD_NAME);
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
        if (isset($rawData['email'])) {
            $this->setEmail($rawData['email']);
        }

        if (isset($rawData['login'])) {
            $this->setLogin($rawData['login']);
        }
    }
}
