<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Macopedia\Allegro\Api\DeliveryMethodRepositoryInterface;
use Macopedia\Allegro\Api\ReturnPolicyRepositoryInterface;
use Macopedia\Allegro\Api\WarrantyRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class responsible for providinig available delivery methods in Allegro
 */
class ReturnPolicy implements OptionSourceInterface
{

    /** @var ReturnPolicyRepositoryInterface */
    private $returnPolicyRepository;

    /**
     * Warranty constructor.
     * @param ReturnPolicyRepositoryInterface $returnPolicyRepository
     */
    public function __construct(ReturnPolicyRepositoryInterface $returnPolicyRepository)
    {
        $this->returnPolicyRepository = $returnPolicyRepository;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        try {
            $returnPolicies = $this->returnPolicyRepository->getList();
        } catch (ClientException $e) {
            return $options;
        }

        foreach ($returnPolicies as $returnPolicy) {
            $options[] = [
                'value' => $returnPolicy->getId(),
                'label' => $returnPolicy->getName()
            ];
        }
        return $options;
    }
}
