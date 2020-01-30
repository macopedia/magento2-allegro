<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Controller\Adminhtml\Orders;

use Macopedia\Allegro\Api\CheckoutFormRepositoryInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\OrderImporter\Processor;
use Macopedia\Allegro\Model\ResourceModel\OrderLog;
use Macopedia\Allegro\Model\ResourceModel\OrderLog\Collection;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Macopedia\Allegro\Model\ResourceModel\OrderLog\CollectionFactory;

/**
 * Import controller class
 */
class Import extends Action
{
    /** @var Processor */
    private $processor;

    /** @var CheckoutFormRepositoryInterface */
    private $checkoutFormRepository;

    /** @var Logger  */
    private $logger;

    /** @var Filter */
    private $filter;

    /** @var CollectionFactory */
    private $collectionFactory;

    /**
     * Import constructor.
     * @param Action\Context $context
     * @param Logger $logger
     * @param Processor $processor
     * @param CheckoutFormRepositoryInterface $checkoutFormRepository
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Action\Context $context,
        Logger $logger,
        Processor $processor,
        CheckoutFormRepositoryInterface $checkoutFormRepository,
        Filter $filter,
        CollectionFactory $collectionFactory
    ) {
        parent::__construct($context);
        $this->logger = $logger;
        $this->processor = $processor;
        $this->checkoutFormRepository = $checkoutFormRepository;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            /** @var Collection $collection */
            $collection = $this->filter->getCollection($this->collectionFactory->create());
        } catch (LocalizedException $e) {
            $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            $result->setPath('noroute');
            return $result;
        }
        /** @var \Macopedia\Allegro\Model\OrderLog $item */
        foreach ($collection as $item) {
            $checkoutFormId = $item->getCheckoutFormId();
            try {
                $checkoutForm = $this->checkoutFormRepository->get($checkoutFormId);
                $this->processor->processOrder($checkoutForm);
                $this->logger->info("Order with id '{$checkoutFormId}' has been successfully created/updated");
                $this->messageManager->addSuccessMessage(__("Order with checkout form ID: %1 has been successfully imported", $checkoutFormId));

            } catch (LocalizedException $e) {
                $this->logger->exception($e);
                $this->messageManager->addExceptionMessage($e);
            } catch (\Exception $e) {
                $this->logger->exception($e);
                $this->messageManager->addErrorMessage(__("Something went wrong while trying to import order with checkout form ID: %1", $checkoutFormId));
            }
        }

        $result = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $result->setPath('*/*/');
        return $result;
    }
}
