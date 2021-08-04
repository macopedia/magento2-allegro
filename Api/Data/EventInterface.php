<?php

namespace Macopedia\Allegro\Api\Data;

interface EventInterface
{
    const TYPE_BOUGHT = 'BOUGHT';
    const TYPE_FILLED_IN = 'FILLED_IN';
    const TYPE_READY_FOR_PROCESSING = 'READY_FOR_PROCESSING';

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id);

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type);

    /**
     * @param string $checkoutFormId
     * @return void
     */
    public function setCheckoutFormId(string $checkoutFormId);

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string|null
     */
    public function getType(): ?string;

    /**
     * @return string|null
     */
    public function getCheckoutFormId(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
