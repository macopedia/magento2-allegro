<?php

namespace Macopedia\Allegro\Api;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface OrderRepositoryInterface extends \Magento\Sales\Api\OrderRepositoryInterface
{
    /**
     * @param string $externalId
     * @return OrderInterface
     * @throws NoSuchEntityException
     */
    public function getByExternalId(string $externalId): OrderInterface;
}
