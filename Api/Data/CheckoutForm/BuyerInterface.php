<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm;

interface BuyerInterface
{
    /**
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName);

    /**
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName);

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email);

    /**
     * @param string $login
     * @return void
     */
    public function setLogin(string $login);

    /**
     * @return string|null
     */
    public function getFirstName(): ?string;

    /**
     * @return string|null
     */
    public function getLastName(): ?string;

    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @return string|null
     */
    public function getLogin(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
