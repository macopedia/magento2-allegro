<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\DeliveryMethodInterface;
use Macopedia\Allegro\Api\Data\DeliveryMethodInterfaceFactory;
use Macopedia\Allegro\Api\DeliveryMethodRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\ResourceModel\Sale\DeliveryMethod;
use Macopedia\Allegro\Model\Api\ClientException;

class DeliveryMethodRepository implements DeliveryMethodRepositoryInterface
{

    /** @var DeliveryMethod */
    private $deliveryMethod;

    /** @var DeliveryMethodInterfaceFactory */
    private $deliveryMethodFactory;

    /**
     * DeliveryMethodRepository constructor.
     * @param DeliveryMethod $deliveryMethod
     * @param DeliveryMethodInterfaceFactory $deliveryMethodFactory
     */
    public function __construct(
        DeliveryMethod $deliveryMethod,
        DeliveryMethodInterfaceFactory $deliveryMethodFactory
    ) {
        $this->deliveryMethod = $deliveryMethod;
        $this->deliveryMethodFactory = $deliveryMethodFactory;
    }

    /**
     * @return DeliveryMethodInterface[]
     * @throws ClientException
     */
    public function getList(): array
    {
        try {

            $deliveryMethodsData = $this->deliveryMethod->getList();

        } catch (ClientResponseException $e) {
            return [];
        }

        $deliveryMethods = [];
        foreach ($deliveryMethodsData as $deliveryMethodData) {
            /** @var DeliveryMethodInterface $deliveryMethod */
            $deliveryMethod = $this->deliveryMethodFactory->create();
            $deliveryMethod->setRawData($deliveryMethodData);
            $deliveryMethods[] = $deliveryMethod;
        }

        return $deliveryMethods;
    }
}
