<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Macopedia\Allegro\Api\ShippingRateRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\Data\OptionSourceInterface;

class ShippingRates implements OptionSourceInterface
{

    /** @var ShippingRateRepositoryInterface */
    private $shippingRateRepository;

    /**
     * ShippingRates constructor.
     * @param ShippingRateRepositoryInterface $shippingRateRepository
     */
    public function __construct(ShippingRateRepositoryInterface $shippingRateRepository)
    {
        $this->shippingRateRepository = $shippingRateRepository;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        try {

            $shippingRates = $this->shippingRateRepository->getList();

        } catch (ClientException $e) {
            return $options;
        }

        foreach ($shippingRates as $shippingRate) {
            $options[] = [
                'value' => $shippingRate->getId(),
                'label' => $shippingRate->getName()
            ];
        }
        return $options;
    }
}
