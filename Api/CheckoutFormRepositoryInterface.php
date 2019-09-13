<?php


namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\Exception\NoSuchEntityException;

interface CheckoutFormRepositoryInterface
{

    /**
     * @param string $checkoutFormId
     * @return CheckoutFormInterface
     * @throws ClientException
     * @throws NoSuchEntityException
     */
    public function get(string $checkoutFormId): CheckoutFormInterface;
}
