<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\CheckoutFormRepositoryInterface;
use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\OrderImporter\Info;
use Macopedia\Allegro\Model\OrderImporter\Processor;

abstract class AbstractOrderImporter
{
    protected $errorsIds = [];
    protected $successIds = [];
    protected $skippedIds = [];

    /** @var Processor */
    protected $processor;

    /** @var Logger  */
    protected $logger;

    /** @var CheckoutFormRepositoryInterface */
    protected $checkoutFormRepository;

    /** @var Info */
    protected $info;

    /**
     * AbstractOrderImporter constructor.
     * @param Logger $logger
     * @param Processor $processor
     * @param CheckoutFormRepositoryInterface $checkoutFormRepository
     * @param Info $info
     */
    public function __construct(
        Logger $logger,
        Processor $processor,
        CheckoutFormRepositoryInterface $checkoutFormRepository,
        Info $info
    ) {
        $this->logger = $logger;
        $this->processor = $processor;
        $this->checkoutFormRepository = $checkoutFormRepository;
        $this->info = $info;
    }

    /**
     * @param $checkoutFormId
     * @return void
     */
    protected function tryToProcessOrder($checkoutFormId)
    {
        try {
            $checkoutForm = $this->checkoutFormRepository->get($checkoutFormId);
            if ($this->processor->processOrder($checkoutForm)) {
                $this->successIds[] = $checkoutFormId;
            } else {
                $this->skippedIds[] = $checkoutFormId;
            }
        } catch (\Exception $e) {
            $this->logger->exception($e);
            $this->errorsIds[] = $checkoutFormId;
        }
    }

    /**
     * @return OrderImporter\Info
     */
    protected function prepareInfoResponse()
    {
        return $this->info
            ->setSuccessIds($this->successIds)
            ->setErrorsIds($this->errorsIds)
            ->setSkippedIds($this->skippedIds);
    }
}
