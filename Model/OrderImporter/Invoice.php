<?php

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Logger\Logger;
use Magento\Framework\DB\Transaction;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Service\InvoiceService;

/**
 * Class responsible for creating invoice to order
 */
class Invoice
{
    /** @var Logger */
    private $logger;

    /** @var InvoiceService */
    private $invoiceService;

    /** @var Transaction */
    private $transaction;

    /**
     * @param Logger $logger
     * @param InvoiceService $invoiceService
     * @param Transaction $transaction
     */
    public function __construct(
        Logger $logger,
        InvoiceService $invoiceService,
        Transaction $transaction
    ) {
        $this->logger = $logger;
        $this->invoiceService = $invoiceService;
        $this->transaction = $transaction;
    }

    /**
     * @param Order $order
     */
    public function create(Order $order)
    {
        if (!$order->canInvoice() || $order->hasInvoices()) {
            return;
        }
        try {
            $invoice = $this->invoiceService->prepareInvoice($order);
            $invoice->register();
            $transactionSave = $this->transaction
                ->addObject($invoice)
                ->addObject($invoice->getOrder());
            $transactionSave->save();
        } catch (\Exception $exception) {
            $this->logger->exception($exception, 'Error while creating invoice');
        }
    }
}
