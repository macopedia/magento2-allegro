<?php

namespace Macopedia\Allegro\Model\Data\Offer;

use Macopedia\Allegro\Api\Data\Offer\AfterSalesServicesInterface;
use Magento\Framework\DataObject;

class AfterSalesServices extends DataObject implements AfterSalesServicesInterface
{

    const IMPLIED_WARRANTY_ID_FIELD_NAME = 'implied_warranty_id';
    const RETURN_POLICY_ID_FIELD_NAME = 'return_policy_id';
    const WARRANTY_ID_FIELD_NAME = 'warranty_id';

    /**
     * @param string $impliedWarrantyId
     */
    public function setImpliedWarrantyId(string $impliedWarrantyId)
    {
        $this->setData(self::IMPLIED_WARRANTY_ID_FIELD_NAME, $impliedWarrantyId);
    }

    /**
     * @param string $returnPolicy
     */
    public function setReturnPolicyId(string $returnPolicy)
    {
        $this->setData(self::RETURN_POLICY_ID_FIELD_NAME, $returnPolicy);
    }

    /**
     * @param string $warrantyId
     */
    public function setWarrantyId(string $warrantyId)
    {
        $this->setData(self::WARRANTY_ID_FIELD_NAME, $warrantyId);
    }

    /**
     * @return string|null
     */
    public function getImpliedWarrantyId(): ?string
    {
        return $this->getData(self::IMPLIED_WARRANTY_ID_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getReturnPolicyId(): ?string
    {
        return $this->getData(self::RETURN_POLICY_ID_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getWarrantyId(): ?string
    {
        return $this->getData(self::WARRANTY_ID_FIELD_NAME);
    }

    /**
     * @param array $rawData
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['impliedWarranty']['id'])) {
            $this->setImpliedWarrantyId($rawData['impliedWarranty']['id']);
        }
        if (isset($rawData['returnPolicy']['id'])) {
            $this->setReturnPolicyId($rawData['returnPolicy']['id']);
        }
        if (isset($rawData['warranty']['id'])) {
            $this->setWarrantyId($rawData['warranty']['id']);
        }
    }

    /**
     * @return array
     */
    public function getRawData(): ?array
    {
        $rawData = [];

        if ($this->getImpliedWarrantyId() !== null) {
            $rawData['impliedWarranty']['id'] = $this->getImpliedWarrantyId();
        }
        if ($this->getReturnPolicyId() !== null) {
            $rawData['returnPolicy']['id'] = $this->getReturnPolicyId();
        }
        if ($this->getWarrantyId() !== null) {
            $rawData['warranty']['id'] = $this->getWarrantyId();
        }

        return count($rawData) > 0 ? $rawData : null;
    }
}
