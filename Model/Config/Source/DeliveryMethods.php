<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Macopedia\Allegro\Api\DeliveryMethodRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class responsible for providinig available delivery methods in Allegro
 */
class DeliveryMethods implements OptionSourceInterface
{

    /** @var DeliveryMethodRepositoryInterface */
    private $deliveryMethodRepository;

    /**
     * DeliveryMethods constructor.
     * @param DeliveryMethodRepositoryInterface $deliveryMethodRepository
     */
    public function __construct(DeliveryMethodRepositoryInterface $deliveryMethodRepository)
    {
        $this->deliveryMethodRepository = $deliveryMethodRepository;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        try {
            $deliveryMethods = $this->deliveryMethodRepository->getList();
        } catch (ClientException $e) {
            return $options;
        }

        foreach ($deliveryMethods as $deliveryMethod) {
            $options[] = [
                'value' => $deliveryMethod->getId(),
                'label' => $deliveryMethod->getName() . ' - ' . $deliveryMethod->getPaymentPolicy()
            ];
        }
        return $options;
    }
}
