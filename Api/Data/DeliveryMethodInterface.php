<?php

namespace Macopedia\Allegro\Api\Data;

interface DeliveryMethodInterface
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
     * @param string $paymentPolicy
     * @return void
     */
    public function setPaymentPolicy(string $paymentPolicy);

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
    public function getPaymentPolicy(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
