<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\OrderLogInterface;
use Magento\Framework\Model\AbstractModel;
use Macopedia\Allegro\Model\ResourceModel\OrderLog as ResourceModel;

class OrderLog extends AbstractModel implements OrderLogInterface
{
    const CHECKOUT_FORM_ID_FIELD = 'checkout_form_id';
    const DATE_OF_FIRST_TRY_FIELD = 'date_of_first_try';
    const DATE_OF_LAST_TRY_FIELD = 'date_of_last_try';
    const REASON_FIELD = 'reason';
    const NUMBER_OF_TRIES_FIELD = 'number_of_tries';

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * {@inheritDoc}
     */
    public function setCheckoutFormId(string $checkoutFormId): void
    {
        $this->setData(self::CHECKOUT_FORM_ID_FIELD, $checkoutFormId);
    }

    /**
     * {@inheritDoc}
     */
    public function setDateOfFirstTry(string $date): void
    {
        $this->setData(self::DATE_OF_FIRST_TRY_FIELD, $date);
    }

    /**
     * {@inheritDoc}
     */
    public function setDateOfLastTry(string $date): void
    {
        $this->setData(self::DATE_OF_LAST_TRY_FIELD, $date);
    }

    /**
     * {@inheritDoc}
     */
    public function setReason(string $reason): void
    {
        $this->setData(self::REASON_FIELD, $reason);
    }

    /**
     * {@inheritDoc}
     */
    public function setNumberOfTries(int $tries): void
    {
        $this->setData(self::NUMBER_OF_TRIES_FIELD, $tries);
    }

    /**
     * {@inheritDoc}
     */
    public function getCheckoutFormId(): ?string
    {
        return $this->getData(self::CHECKOUT_FORM_ID_FIELD);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateOfFirstTry(): ?string
    {
        return $this->getData(self::DATE_OF_FIRST_TRY_FIELD);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateOfLastTry(): ?string
    {
        return $this->getData(self::DATE_OF_LAST_TRY_FIELD);
    }

    /**
     * {@inheritDoc}
     */
    public function getReason(): ?string
    {
        return $this->getData(self::REASON_FIELD);
    }

    /**
     * {@inheritDoc}
     */
    public function getNumberOfTries(): int
    {
        return (int)$this->getData(self::NUMBER_OF_TRIES_FIELD);
    }
}
