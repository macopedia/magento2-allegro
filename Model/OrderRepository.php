<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\OrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Payment\Api\Data\PaymentAdditionalInfoInterfaceFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Magento\Sales\Model\ResourceModel\Metadata;
use Magento\Sales\Model\ResourceModel\Order\Collection;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Tax\Api\OrderTaxManagementInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;

class OrderRepository extends \Magento\Sales\Model\OrderRepository implements OrderRepositoryInterface
{

    /** @var CollectionFactory */
    private $orderCollectionFactory;

    /**
     * OrderRepository constructor.
     * @param Metadata $metadata
     * @param SearchResultFactory $searchResultFactory
     * @param CollectionFactory $orderCollectionFactory
     * @param CollectionProcessorInterface|null $collectionProcessor
     * @param OrderExtensionFactory|null $orderExtensionFactory
     * @param OrderTaxManagementInterface|null $orderTaxManagement
     * @param PaymentAdditionalInfoInterfaceFactory|null $paymentAdditionalInfoFactory
     * @param JsonSerializer|null $serializer
     */
    public function __construct(
        Metadata $metadata,
        SearchResultFactory $searchResultFactory,
        CollectionFactory $orderCollectionFactory,
        CollectionProcessorInterface $collectionProcessor = null,
        OrderExtensionFactory $orderExtensionFactory = null,
        OrderTaxManagementInterface $orderTaxManagement = null,
        PaymentAdditionalInfoInterfaceFactory $paymentAdditionalInfoFactory = null,
        JsonSerializer $serializer = null
    ) {
        parent::__construct(
            $metadata,
            $searchResultFactory,
            $collectionProcessor,
            $orderExtensionFactory,
            $orderTaxManagement,
            $paymentAdditionalInfoFactory,
            $serializer
        );

        $this->orderCollectionFactory = $orderCollectionFactory;
    }

    /**
     * @param string $externalId
     * @return OrderInterface
     * @throws NoSuchEntityException
     */
    public function getByExternalId(string $externalId): OrderInterface
    {
        // TODO change collection use to getList method call in parent

        /** @var Collection $collection */
        $collection = $this->orderCollectionFactory->create();
        $collection->addFieldToFilter('external_id', ['eq' => $externalId]);
        $collection->load();

        if ($collection->count() != 1) {
            throw new NoSuchEntityException(__('Requested order with external id "%1" does not exist', $externalId));
        }

        return $collection->getFirstItem();
    }
}
