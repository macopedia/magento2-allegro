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
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Tax\Api\OrderTaxManagementInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Macopedia\Allegro\Model\ResourceModel\Order as ResourceModel;

class OrderRepository extends \Magento\Sales\Model\OrderRepository implements OrderRepositoryInterface
{

    /** @var ResourceModel */
    private $resourceModel;

    /**
     * OrderRepository constructor.
     * @param Metadata $metadata
     * @param SearchResultFactory $searchResultFactory
     * @param ResourceModel $resourceModel
     * @param CollectionProcessorInterface|null $collectionProcessor
     * @param OrderExtensionFactory|null $orderExtensionFactory
     * @param OrderTaxManagementInterface|null $orderTaxManagement
     * @param PaymentAdditionalInfoInterfaceFactory|null $paymentAdditionalInfoFactory
     * @param JsonSerializer|null $serializer
     */
    public function __construct(
        Metadata $metadata,
        SearchResultFactory $searchResultFactory,
        ResourceModel $resourceModel,
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
        $this->resourceModel = $resourceModel;
    }

    /**
     * @param string $externalId
     * @return OrderInterface
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     */
    public function getByExternalId(string $externalId): OrderInterface
    {
        $orderId = $this->resourceModel->getIdByAllegroCheckoutFormId($externalId);
        if (!$orderId) {
            throw new NoSuchEntityException(
                __("The order that was requested doesn't exist. Verify the product and try again.")
            );
        }
        return $this->get($orderId);
    }
}
