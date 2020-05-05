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
use Magento\Framework\Data\Collection;

/**
 * Class responsible for processing orders with errors form Allegro
 */
class OrderWithErrorImporter extends AbstractOrderImporter
{
    const PAGE_SIZE = 200;

    /** @var SearchCriteriaBuilder  */
    private $searchCriteriaBuilder;

    /** @var OrderLogRepositoryInterface */
    private $orderLogRepository;

    /** @var Collection */
    private $collection;

    /**
     * OrderWithErrorImporter constructor.
     * @param Logger $logger
     * @param Processor $processor
     * @param CheckoutFormRepositoryInterface $checkoutFormRepository
     * @param Info $info
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param OrderLogRepositoryInterface $orderLogRepository
     * @param Collection $collection
     */
    public function __construct(
        Logger $logger,
        Processor $processor,
        CheckoutFormRepositoryInterface $checkoutFormRepository,
        Info $info,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderLogRepositoryInterface $orderLogRepository,
        Collection $collection
    ) {
        parent::__construct($logger, $processor, $checkoutFormRepository, $info);
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderLogRepository = $orderLogRepository;
        $this->collection = $collection;
    }

    /**
     * @return Info
     */
    public function execute() : Info
    {
        $lastPageNumber = ceil($this->orderLogRepository->getCount() / self::PAGE_SIZE);

        for ($i = 1; $i <= $lastPageNumber; $i++) {
            $orders = $this->getOrderLogPage($i);
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
    private function getOrderLogPage(int $pageNumber)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('number_of_tries', 10, 'lteq')
            ->setPageSize(self::PAGE_SIZE)
            ->setCurrentPage($pageNumber)
            ->create();

        return $this->orderLogRepository->getList($searchCriteria);
    }
}
