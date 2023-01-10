<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\ShippingRateInterface;
use Macopedia\Allegro\Api\Data\ShippingRateInterfaceFactory;
use Macopedia\Allegro\Api\ShippingRateRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\ResourceModel\Sale\ShippingRates;

class ShippingRateRepository implements ShippingRateRepositoryInterface
{
    /** @var ShippingRates */
    private $shippingRates;

    /** @var ShippingRateInterfaceFactory */
    private $shippingRateFactory;

    /**
     * ShippingRatesRepository constructor.
     * @param ShippingRates $shippingRates
     * @param ShippingRateInterfaceFactory $shippingRateFactory
     */
    public function __construct(
        ShippingRates $shippingRates,
        ShippingRateInterfaceFactory $shippingRateFactory
    ) {
        $this->shippingRates = $shippingRates;
        $this->shippingRateFactory = $shippingRateFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getList(): array
    {
        try {

            $shippingRatesData = $this->shippingRates->getList();

        } catch (ClientResponseException $e) {
            return [];
        }

        $shippingRates = [];
        foreach ($shippingRatesData as $shippingRateData) {
            /** @var ShippingRateInterface $shippingRate */
            $shippingRate = $this->shippingRateFactory->create();
            $shippingRate->setRawData($shippingRateData);
            $shippingRates[] = $shippingRate;
        }

        return $shippingRates;
    }
}
