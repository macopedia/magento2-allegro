<?php

namespace Macopedia\Allegro\Model\ResourceModel\Order;

use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\ResourceModel\AbstractResource;

/**
 * Resource model to get events from Allegro API
 */
class Events extends AbstractResource
{
    /**
     * @return mixed
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getList()
    {
        $response = $this->requestGet('/order/events', [], true);
        return $response['events'] ?? [];
    }

    /**
     * @param string $from
     * @return mixed
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getListFrom(string $from)
    {
        $response = $this->requestGet('/order/events?from=' . $from, [], true);
        return $response['events'] ?? [];
    }
}
