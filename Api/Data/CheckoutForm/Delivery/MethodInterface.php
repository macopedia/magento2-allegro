<?php

namespace Macopedia\Allegro\Api\Data\CheckoutForm\Delivery;

interface MethodInterface
{
    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id);

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
