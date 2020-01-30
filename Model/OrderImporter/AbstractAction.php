<?php

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Magento\Quote\Model\QuoteFactory;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Framework\DataObject\Copy;
use Magento\Quote\Model\Quote\TotalsCollector;
use Magento\Framework\Event\ManagerInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Sales\Model\Config as SalesConfig;
use Magento\Tax\Model\Config as TaxConfig;
use Magento\Quote\Api\Data\CartExtensionFactory;

abstract class AbstractAction
{

    /** @var Status */
    protected $status;

    /** @var Invoice */
    protected $invoice;

    /** @var Shipping */
    protected $shipping;

    /** @var Payment */
    protected $payment;

    /** @var OrderRepositoryInterface */
    protected $orderRepository;

    /** @var QuoteFactory */
    protected $quoteFactory;

    /** @var Copy */
    protected $objectCopyService;

    /** @var TotalsCollector */
    protected $totalsCollector;

    /** @var ManagerInterface */
    protected $eventManager;

    /** @var ProductFactory */
    protected $productFactory;

    /** @var Json */
    protected $jsonSerializer;

    /** @var SalesConfig */
    protected $salesConfig;

    /** @var TaxConfig */
    protected $taxConfig;

    /** @var CartExtensionFactory */
    private $cartExtensionFactory;

    /**
     * AbstractAction constructor.
     * @param Shipping $shipping
     * @param Payment $payment
     * @param Status $status
     * @param Invoice $invoice
     * @param OrderRepositoryInterface $orderRepository
     * @param QuoteFactory $quoteFactory
     * @param Copy $objectCopyService
     * @param TotalsCollector $totalsCollector
     * @param ManagerInterface $eventManager
     * @param ProductFactory $productFactory
     * @param Json $jsonSerializer
     * @param SalesConfig $salesConfig
     * @param TaxConfig $taxConfig
     * @param CartExtensionFactory $cartExtensionFactory
     */
    public function __construct(
        Shipping $shipping,
        Payment $payment,
        Status $status,
        Invoice $invoice,
        OrderRepositoryInterface $orderRepository,
        QuoteFactory $quoteFactory,
        Copy $objectCopyService,
        TotalsCollector $totalsCollector,
        ManagerInterface $eventManager,
        ProductFactory $productFactory,
        Json $jsonSerializer,
        SalesConfig $salesConfig,
        TaxConfig $taxConfig,
        CartExtensionFactory $cartExtensionFactory
    ) {
        $this->shipping = $shipping;
        $this->payment = $payment;
        $this->status = $status;
        $this->invoice = $invoice;
        $this->orderRepository = $orderRepository;
        $this->quoteFactory = $quoteFactory;
        $this->objectCopyService = $objectCopyService;
        $this->totalsCollector = $totalsCollector;
        $this->eventManager = $eventManager;
        $this->productFactory = $productFactory;
        $this->jsonSerializer = $jsonSerializer;
        $this->salesConfig = $salesConfig;
        $this->taxConfig = $taxConfig;
        $this->cartExtensionFactory = $cartExtensionFactory;
    }

    /**
     * @param OrderInterface $order
     * @param CheckoutFormInterface $checkoutForm
     */
    protected function processStatus(OrderInterface $order, CheckoutFormInterface $checkoutForm)
    {
        $status = $this->status->get($checkoutForm);

        if ($status[Status::STATE_KEY] != Order::STATE_NEW) {
            $order
                ->setStatus($status[Status::STATUS_KEY])
                ->setState($status[Status::STATE_KEY]);
        }

        if ($status[Status::PAID_KEY]) {
            $this->invoice->create($order);
        }
    }

    /**
     * @param OrderInterface $order
     * @param CheckoutFormInterface $checkoutForm
     */
    protected function processTotals(OrderInterface $order, CheckoutFormInterface $checkoutForm)
    {
        return;
        // TODO: This functionality requires refactor
        $quote = $this->quoteFactory->create();

        $orderItems = $order->getItemsCollection($this->salesConfig->getAvailableProductTypes(), true);
        foreach ($orderItems as $orderItem) {
            if ($orderItem->getParentItem()) {
                continue;
            }
            $qty = $orderItem->getQtyOrdered();
            if ($qty <= 0) {
                continue;
            }
            if (!$orderItem->getId()) {
                continue;
            }
            /** @var Product $product */
            $product = $this->productFactory->create()
                ->setStoreId($order->getStoreId())
                ->load($orderItem->getProductId());
            if (!$product->getId()) {
                continue;
            }
            $product->setSkipCheckRequiredOption(true);
            $buyRequest = $orderItem->getBuyRequest();
            $product->setPrice($orderItem->getPrice());
            $item = $quote->addProduct($product, $buyRequest);
            $item->setOrderItemId($orderItem->getId());
            if (is_string($item)) {
                throw new \Exception(__($item)); // TODO test
            }
            if ($additionalOptions = $orderItem->getProductOptionByCode('additional_options')) {
                $item->addOption(
                    new \Magento\Framework\DataObject(
                        [
                            'product' => $item->getProduct(),
                            'code' => 'additional_options',
                            'value' => $this->jsonSerializer->serialize($additionalOptions),
                        ]
                    )
                );
            }
            $this->eventManager->dispatch(
                'sales_convert_order_item_to_quote_item',
                ['order_item' => $orderItem, 'quote_item' => $item]
            );
        }

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_copy_order_billing_address',
            'to_order',
            $order->getBillingAddress(),
            $quote->getBillingAddress()
        );

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_copy_order_shipping_address',
            'to_order',
            $order->getShippingAddress(),
            $quote->getShippingAddress()
        );
        $quote->getShippingAddress()->setShippingMethod($order->getShippingMethod());
        $quote->getShippingAddress()->setShippingDescription($order->getShippingDescription());
        $quote->getShippingAddress()->unsCachedItemsAll();

        $this->objectCopyService->copyFieldsetToTarget(
            'sales_copy_order',
            'to_edit',
            $order,
            $quote
        );
        $this->eventManager->dispatch(
            'sales_convert_order_to_quote',
            ['order' => $order, 'quote' => $quote]
        );

        $cartExtension = $quote->getExtensionAttributes();
        if ($cartExtension === null) {
            $cartExtension = $this->cartExtensionFactory->create();
        }
        $cartExtension->setExternalId($order->getExternalId());
        $cartExtension->setOrderFrom($order->getOrderFrom());
        $quote->setExtensionAttributes($cartExtension);

        $quote->setAllegroShippingPrice($checkoutForm->getDelivery()->getCost()->getAmount());


        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->getShippingAddress()->collectShippingRates();
        $quote->setTotalsCollectedFlag(false);

        $this->taxConfig->setShippingPriceIncludeTax(true);
        $this->taxConfig->setPriceIncludesTax(true);
        if (count($quote->getShippingAddress()->getAllItems()) > 0) {
            $order->addData(
                $this->totalsCollector->collectAddressTotals(
                    $quote,
                    $quote->getShippingAddress()
                )
                ->getData()
            );
        } else {
            $order->addData(
                $this->totalsCollector->collectAddressTotals(
                    $quote,
                    $quote->getBillingAddress()
                )
                    ->getData()
            );
        }
        $totals = $this->totalsCollector->collect($quote);
        $order->addData($totals->getData());
        foreach ($quote->getAllItems() as $item) {
            $orderItem = $orderItems->getItemById($item->getOrderItemId());
            $orderItem->setPrice($item->getPrice());
            $orderItem->setBasePrice($item->getBasePrice());
            $orderItem->setOriginalPrice($item->getOriginalPrice());
            $orderItem->setBaseOriginalPrice($item->getBaseOriginalPrice());
            $orderItem->setTaxPercent($item->getTaxPercent());
            $orderItem->setTaxAmount($item->getTaxAmount());
            $orderItem->setBaseTaxAmount($item->getBaseTaxAmount());
            $orderItem->setRowTotal($item->getRowTotal());
            $orderItem->setBaseRowTotal($item->getBaseRowTotal());
            $orderItem->setPriceInclTax($item->getPriceInclTax());
            $orderItem->setBasePriceInclTax($item->getBasePriceInclTax());
            $orderItem->setRowTotalInclTax($item->getRowTotalInclTax());
            $orderItem->setBaseRowTotalInclTax($item->getBaseRowTotalInclTax());
            $orderItem->save();
        }
        $this->taxConfig->setShippingPriceIncludeTax(null);
        $this->taxConfig->setPriceIncludesTax(null);

        return;
    }

    /**
     * @param OrderInterface $order
     * @param CheckoutFormInterface $checkoutForm
     */
    protected function processComments(OrderInterface $order, CheckoutFormInterface $checkoutForm)
    {
        $messageToSeller = $checkoutForm->getMessageToSeller();
        if ($messageToSeller == null) {
            return;
        }

        $statusHistories = $order->getStatusHistories();
        if (array_search($messageToSeller, $statusHistories) === false) {
            $order->addStatusHistoryComment($messageToSeller);
        }
    }
}
