<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\WarrantyInterface;
use Macopedia\Allegro\Api\Data\WarrantyInterfaceFactory;
use Macopedia\Allegro\Api\WarrantyRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\ResourceModel\Sale\AfterSaleServices;

class WarrantyRepository implements WarrantyRepositoryInterface
{

    /** @var AfterSaleServices */
    private $afterSaleServices;

    /** @var WarrantyInterfaceFactory */
    private $warrantyFactory;

    /**
     * WarrantyRepository constructor.
     * @param AfterSaleServices $afterSaleServices
     * @param WarrantyInterfaceFactory $warrantyFactory
     */
    public function __construct(
        AfterSaleServices $afterSaleServices,
        WarrantyInterfaceFactory $warrantyFactory
    ) {
        $this->afterSaleServices = $afterSaleServices;
        $this->warrantyFactory = $warrantyFactory;
    }

    /**
     * @return WarrantyInterface[]
     * @throws ClientException
     */
    public function getList(): array
    {
        try {

            $warrantiesData = $this->afterSaleServices->getWarrantiesList();

        } catch (ClientResponseException $e) {
            return [];
        }

        $warranties = [];
        foreach ($warrantiesData as $warrantyData) {
            /** @var WarrantyInterface $warranty */
            $warranty = $this->warrantyFactory->create();
            $warranty->setRawData($warrantyData);
            $warranties[] = $warranty;
        }
        return $warranties;
    }
}
