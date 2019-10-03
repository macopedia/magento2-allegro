<?php

namespace Macopedia\Allegro\Model\ResourceModel\Order;

use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\ResourceModel\AbstractResource;

/**
 * Resource model to get the latest event
 */
class EventStats extends AbstractResource
{

    /**
     * @return mixed
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getLastEvent()
    {
        $response = $this->requestGet('/order/event-stats', [], false);
        return $response['latestEvent'] ?? [];
    }
}
