<?php

namespace Macopedia\Allegro\Model\ResourceModel\Sale;

use Macopedia\Allegro\Model\ResourceModel\AbstractResource;

class AfterSaleServices extends AbstractResource
{

    /**
     * @return array
     * @throws \Macopedia\Allegro\Model\Api\ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getImpliedWarrantiesList()
    {
        $result = $this->requestGet('/after-sales-service-conditions/implied-warranties?seller.id=' . $this->getCurrentUserId());
        return $result['impliedWarranties'];
    }

    /**
     * @return array
     * @throws \Macopedia\Allegro\Model\Api\ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getReturnPoliciesList()
    {
        $result = $this->requestGet('/after-sales-service-conditions/return-policies?seller.id=' . $this->getCurrentUserId());
        return $result['returnPolicies'];

    }

    /**
     * @return array
     * @throws \Macopedia\Allegro\Model\Api\ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getWarrantiesList()
    {
        $result = $this->requestGet('/after-sales-service-conditions/warranties?seller.id=' . $this->getCurrentUserId());
        return $result['warranties'];
    }
}
