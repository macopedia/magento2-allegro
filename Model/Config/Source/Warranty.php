<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Macopedia\Allegro\Api\DeliveryMethodRepositoryInterface;
use Macopedia\Allegro\Api\WarrantyRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class responsible for providinig available delivery methods in Allegro
 */
class Warranty implements OptionSourceInterface
{

    /** @var WarrantyRepositoryInterface */
    private $warrantyRepository;

    /**
     * Warranty constructor.
     * @param WarrantyRepositoryInterface $warrantyRepository
     */
    public function __construct(WarrantyRepositoryInterface $warrantyRepository)
    {
        $this->warrantyRepository = $warrantyRepository;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        try {
            $warranties = $this->warrantyRepository->getList();
        } catch (ClientException $e) {
            return $options;
        }

        foreach ($warranties as $warranty) {
            $options[] = [
                'value' => $warranty->getId(),
                'label' => $warranty->getName()
            ];
        }
        return $options;
    }
}
