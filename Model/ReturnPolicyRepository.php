<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\ReturnPolicyInterface;
use Macopedia\Allegro\Api\Data\ReturnPolicyInterfaceFactory;
use Macopedia\Allegro\Api\ReturnPolicyRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\ResourceModel\Sale\AfterSaleServices;

class ReturnPolicyRepository implements ReturnPolicyRepositoryInterface
{

    /** @var AfterSaleServices */
    private $afterSaleServices;

    /** @var ReturnPolicyInterfaceFactory */
    private $returnPolicyFactory;

    /**
     * ReturnPolicyRepository constructor.
     * @param AfterSaleServices $afterSaleServices
     * @param ReturnPolicyInterfaceFactory $returnPolicyFactory
     */
    public function __construct(
        AfterSaleServices $afterSaleServices,
        ReturnPolicyInterfaceFactory $returnPolicyFactory
    ) {
        $this->afterSaleServices = $afterSaleServices;
        $this->returnPolicyFactory = $returnPolicyFactory;
    }

    /**
     * @return ReturnPolicyInterface[]
     * @throws ClientException
     */
    public function getList(): array
    {
        try {

            $returnPoliciesData = $this->afterSaleServices->getReturnPoliciesList();

        } catch (ClientResponseException $e) {
            return [];
        }

        $returnPolicies = [];
        foreach ($returnPoliciesData as $returnPolicyData) {
            /** @var ReturnPolicyInterface $returnPolicy */
            $returnPolicy = $this->returnPolicyFactory->create();
            $returnPolicy->setRawData($returnPolicyData);
            $returnPolicies[] = $returnPolicy;
        }
        return $returnPolicies;
    }
}
