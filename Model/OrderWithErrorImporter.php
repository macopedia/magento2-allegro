<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\CheckoutFormRepositoryInterface;
use Macopedia\Allegro\Api\Data\OrderLogInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\OrderImporter\Info;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Macopedia\Allegro\Api\OrderLogRepositoryInterface;
use Macopedia\Allegro\Model\OrderImporter\Processor;

/**
 * Class responsible for processing orders with errors form Allegro
 */
class OrderWithErrorImporter extends AbstractOrderImporter
{
    /** @var SearchCriteriaBuilder  */
    private $searchCriteriaBuilder;

    /** @var OrderLogRepositoryInterface */
    private $orderLogRepository;

    /**
     * OrderWithErrorImporter constructor.
     * @param Logger $logger
     * @param Processor $processor
     * @param CheckoutFormRepositoryInterface $checkoutFormRepository
     * @param Info $info
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param OrderLogRepositoryInterface $orderLogRepository
     */
    public function __construct(
        Logger $logger,
        Processor $processor,
        CheckoutFormRepositoryInterface $checkoutFormRepository,
        Info $info,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderLogRepositoryInterface $orderLogRepository
    ) {
        parent::__construct($logger, $processor, $checkoutFormRepository, $info);
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderLogRepository = $orderLogRepository;
    }

    /**
     * @return Info
     */
    public function execute() : Info
    {
        for ($i = 1; $i <= ceil($this->orderLogRepository->getCount() / 200 ); $i += 1) {
            $orders = $this->getOrderLoadPage($i);
            foreach ($orders as $order) {
                $checkoutFormId = $order->getCheckoutFormId();
                $this->tryToProcessOrder($checkoutFormId);
            }
        }

        return $this->prepareInfoResponse();
    }

    /**
     * @param int $pageNumber
     * @return OrderLogInterface[]
     */
    private function getOrderLoadPage(int $pageNumber)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('number_of_tries', 10, 'lteq')
            ->setPageSize(200)
            ->setCurrentPage($pageNumber)
            ->create();

        return $this->orderLogRepository->getList($searchCriteria);
    }
}