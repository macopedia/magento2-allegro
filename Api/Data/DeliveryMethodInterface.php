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
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getName(): ?string;

    /**
     * @return string
     */
    public function getPaymentPolicy(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
