<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Macopedia\Allegro\Api\ImpliedWarrantyRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class responsible for providinig available delivery methods in Allegro
 */
class ImpliedWarranty implements OptionSourceInterface
{

    /** @var ImpliedWarrantyRepositoryInterface */
    private $impliedWarrantyRepository;

    /**
     * ImpliedWarranty constructor.
     * @param ImpliedWarrantyRepositoryInterface $impliedWarrantyRepository
     */
    public function __construct(ImpliedWarrantyRepositoryInterface $impliedWarrantyRepository)
    {
        $this->impliedWarrantyRepository = $impliedWarrantyRepository;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [];

        try {
            $impliedWarranties = $this->impliedWarrantyRepository->getList();
        } catch (ClientException $e) {
            return $options;
        }

        foreach ($impliedWarranties as $impliedWarranty) {
            $options[] = [
                'value' => $impliedWarranty->getId(),
                'label' => $impliedWarranty->getName()
            ];
        }
        return $options;
    }
}
