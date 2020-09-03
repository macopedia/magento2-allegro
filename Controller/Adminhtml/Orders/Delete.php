<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Controller\Adminhtml\Orders;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\OrderLog;
use Macopedia\Allegro\Model\ResourceModel\OrderLog\Collection;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Macopedia\Allegro\Model\ResourceModel\OrderLog\CollectionFactory;
use Macopedia\Allegro\Model\OrderLogRepository;

/**
 * Delete controller class
 */
class Delete extends Action
{
    /** @var Logger */
    private $logger;

    /** @var Filter */
    private $filter;

    /** @var CollectionFactory */
    private $collectionFactory;

    /** @var OrderLogRepository */
    private $orderLogRepository;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param Logger $logger
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param OrderLogRepository $orderLogRepository
     */
    public function __construct(
        Action\Context $context,
        Logger $logger,
        Filter $filter,
        CollectionFactory $collectionFactory,
        OrderLogRepository $orderLogRepository
    ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->orderLogRepository = $orderLogRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $result->setPath('*/*/');

        try {
            /** @var Collection $collection */
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collection->addFieldToSelect('checkout_form_id');
        } catch (LocalizedException $e) {
            $this->messageManager->addExceptionMessage($e);
            return $result;
        }
        /** @var OrderLog $item */
        foreach ($collection as $item) {
            $checkoutFormId = $item->getCheckoutFormId();
            try {
                $this->orderLogRepository->deleteByCheckoutFormId($checkoutFormId);
                $this->logger->info(__(
                    "Order log with checkout form ID: %1 has been successfully deleted",
                    $checkoutFormId
                ));
                $this->messageManager->addSuccessMessage(__(
                    "Order log with checkout form ID: %1 has been successfully deleted",
                    $checkoutFormId
                ));
            } catch (CouldNotDeleteException $e) {
                $this->logger->exception($e);
                $this->messageManager->addErrorMessage(__(
                    "Something went wrong while trying to delete order log with checkout form ID: %1",
                    $checkoutFormId
                ));
            }
        }
        return $result;
    }
}
