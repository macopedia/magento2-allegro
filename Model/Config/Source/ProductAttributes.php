<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface;

/**
 * Product attributes config source model
 */
class ProductAttributes implements OptionSourceInterface
{
    /** @var SearchCriteriaBuilder */
    private $searchCriteriaBuilder;

    /** @var AttributeRepositoryInterface */
    private $attributesRepository;

    /** @var ManagerInterface */
    private $messageManager;

    /**
     * ProductAttributes constructor.
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param AttributeRepositoryInterface $attributesRepository
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AttributeRepositoryInterface $attributesRepository,
        ManagerInterface $messageManager
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->attributesRepository = $attributesRepository;
        $this->messageManager = $messageManager;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        try {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $attributes = $this->attributesRepository->getList(Product::ENTITY, $searchCriteria);
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage('Can\'t get list of product attributes');
            $this->messageManager->addErrorMessage($e);
            return [];
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage('Can\'t get list of product attributes');
            return [];
        }

        $options = [];
        foreach ($attributes->getItems() as $attribute) {
            $options[] = [
                'label' => $attribute->getDefaultFrontendLabel(),
                'value' => $attribute->getAttributeCode(),
            ];
        }
        return $options;
    }
}
