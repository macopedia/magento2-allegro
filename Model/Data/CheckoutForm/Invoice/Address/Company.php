<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm\Invoice\Address;

use Macopedia\Allegro\Api\Data\CheckoutForm\Invoice\Address\CompanyInterface;
use Magento\Framework\DataObject;

class Company extends DataObject implements CompanyInterface
{

    const NAME_FIELD_NAME = 'name';
    const VAT_ID_FIELD_NAME = 'vat_id';

    /**
     * {@inheritDoc}
     */
    public function setName(string $name)
    {
        $this->setData(self::NAME_FIELD_NAME, $name);
    }

    /**
     * {@inheritDoc}
     */
    public function setVatId(string $vatId)
    {
        $this->setData(self::VAT_ID_FIELD_NAME, $vatId);
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getVatId(): ?string
    {
        return $this->getData(self::VAT_ID_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['name'])) {
            $this->setName($rawData['name']);
        }
        if (isset($rawData['taxId'])) {
            $this->setVatId($rawData['taxId']);
        }
    }
}
