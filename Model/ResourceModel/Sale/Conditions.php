<?php

namespace Macopedia\Allegro\Model\ResourceModel\Sale;

use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\ResourceModel\AbstractResource;

/**
 * Resource model to get offers conditions from Allegro API
 */
class Conditions extends AbstractResource
{
    /**
     * @param string $sellerId
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getWarranties($sellerId)
    {
        return $this->requestGet('/after-sales-service-conditions/warranties?seller.id=' . $sellerId);
    }

    /**
     * @param string $sellerId
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getReturnPolicies($sellerId)
    {
        return $this->requestGet('/after-sales-service-conditions/return-policies?seller.id=' . $sellerId);
    }

    /**
     * @param string $sellerId
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getImpliedWarranties($sellerId)
    {
        return $this->requestGet('/after-sales-service-conditions/implied-warranties?seller.id=' . $sellerId);
    }

    /**
     * @param string $sellerId
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getAdditionalServiced($sellerId)
    {
        return $this->requestGet('/sale/offer-additional-services/groups?user.id=' . $sellerId);
    }

    /**
     * @param string $sellerId
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getContacts($sellerId)
    {
        return $this->requestGet('/sale/offer-contacts?seller.id=' . $sellerId);
    }
}
