<?php

namespace Macopedia\Allegro\Api\Data\Offer;

interface AfterSalesServicesInterface
{
    /**
     * @param string $impliedWarrantyId
     * @return void
     */
    public function setImpliedWarrantyId(string $impliedWarrantyId);

    /**
     * @param string $returnPolicy
     * @return void
     */
    public function setReturnPolicyId(string $returnPolicy);

    /**
     * @param string $warrantyId
     * @return void
     */
    public function setWarrantyId(string $warrantyId);

    /**
     * @return string|null
     */

    public function getImpliedWarrantyId(): ?string;

    /**
     * @return string|null
     */

    public function getReturnPolicyId(): ?string;

    /**
     * @return string|null
     */

    public function getWarrantyId(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);

    /**
     * @return array|null
     */
    public function getRawData(): ?array;
}
