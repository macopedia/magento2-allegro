<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\DeliveryMethodInterface;
use Macopedia\Allegro\Model\Api\ClientException;

interface DeliveryMethodRepositoryInterface
{

    /**
     * @return DeliveryMethodInterface[]
     * @throws ClientException
     */
    public function getList(): array;
}
