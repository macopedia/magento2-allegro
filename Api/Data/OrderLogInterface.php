<?php

namespace Macopedia\Allegro\Api\Data;

interface OrderLogInterface
{
    /**
     * @param string $checkoutFormId
     * @return void
     */
    public function setCheckoutFormId(string $checkoutFormId) : void;

    /**
     * @param string $date
     * @return void
     */
    public function setDateOfFirstTry(string $date) : void;

    /**
     * @param string $date
     * @return void
     */
    public function setDateOfLastTry(string $date) : void;

    /**
     * @param string $reason
     * @return void
     */
    public function setReason(string $reason) : void;

    /**
     * @param int $tries
     * @return void
     */
    public function setNumberOfTries(int $tries) : void;

    /**
     * @return string|null
     */
    public function getCheckoutFormId() : ?string;

    /**
     * @return string|null
     */
    public function getDateOfFirstTry() : ?string;

    /**
     * @return string|null
     */
    public function getDateOfLastTry() : ?string;

    /**
     * @return string|null
     */
    public function getReason() : ?string;

    /**
     * @return int|null
     */
    public function getNumberOfTries() : ?int;

}
