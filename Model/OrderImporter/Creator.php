<?php

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Api\Data\CheckoutForm\LineItemInterface;
use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Macopedia\Allegro\Api\ProductRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\EntityManager\EventManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\QuoteManagement;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Magento order creator
 */
class Creator extends AbstractAction
{
    const STORE_ID_CONFIG_KEY = 'allegro/order/store';
    const EVENT_NAME = 'allegro_order_import_before_quote_save';

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var Customer */
    private $customer;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var QuoteFactory */
    private $quoteFactory;

    /** @var QuoteManagement */
    private $quoteManagement;

    /** @var EventManager */
    private $eventManager;

    /**
     * @param OrderRepositoryInterface $orderRepository
     * @param StoreManagerInterface $storeManager
     * @param Customer $customer
     * @param ScopeConfigInterface $scopeConfig
     * @param QuoteFactory $quoteFactory
     * @param QuoteManagement $quoteManagement
     * @param Shipping $shipping
     * @param Payment $payment
     * @param Status $status
     * @param Invoice $invoice
     * @param EventManager $eventManager
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        Customer $customer,
        ScopeConfigInterface $scopeConfig,
        QuoteFactory $quoteFactory,
        QuoteManagement $quoteManagement,
        Shipping $shipping,
        Payment $payment,
        Status $status,
        Invoice $invoice,
        EventManager $eventManager
    ) {
        // TODO Parent construct call is missing
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        $this->customer = $customer;
        $this->scopeConfig = $scopeConfig;
        $this->quoteFactory = $quoteFactory;
        $this->quoteManagement = $quoteManagement;
        $this->shipping = $shipping;
        $this->payment = $payment;
        $this->status = $status;
        $this->invoice = $invoice;
        $this->eventManager = $eventManager;
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @return bool
     * @throws CreatorItemsException
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute(CheckoutFormInterface $checkoutForm)
    {
        /** @var Quote $quote */
        $quote = $this->quoteFactory->create();
        $quote->setStore($store = $this->getStore());

        $this->processCustomer($quote, $checkoutForm);
        $lineItemsIds = $this->processItems($quote, $checkoutForm);

        if (empty($quote->getAllItems())) {
            return false;
        }

        $this->processShipping($quote, $checkoutForm);
        $this->processBilling($quote, $checkoutForm);

        $order = $this->placeOrder($quote, $checkoutForm);
        $order->setCanSendNewEmailFlag(false);

        foreach ($order->getItems() as $item) {
            $item->setData('allegro_line_item_id', $lineItemsIds[$item->getSku()]);
        }

        $this->processTotals($order, $checkoutForm);
        $this->processStatus($order, $checkoutForm);
        $this->processComments($order, $checkoutForm);

        $checkoutForm->getDelivery()->getPickupPoint()->fillOrder($order);

        $this->orderRepository->save($order);

        return $order->getId();
    }

    /**
     * @param Quote $quote
     * @param CheckoutFormInterface $checkoutForm
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function processCustomer(Quote $quote, CheckoutFormInterface $checkoutForm)
    {
        $customer = $this->customer->get(
            $checkoutForm->getBuyer(),
            $store = $this->getStore(),
            $checkoutForm->getInvoice()->getAddress()->getCompany()->getVatId()
        );
        $quote->assignCustomer($customer);
    }

    /**
     * @param Quote $quote
     * @param CheckoutFormInterface $checkoutForm
     * @return array
     * @throws CreatorItemsException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function processItems(Quote $quote, CheckoutFormInterface $checkoutForm)
    {
        $lineItemsIds = [];
        foreach ($checkoutForm->getLineItems() as $lineItem) {
            if (!$this->isValidLineItem($lineItem)) {
                throw new CreatorItemsException(
                    __('Invalid item data in received from Allegro API response')
                );
            }

            $offerId = $lineItem->getOfferId();
            if (!$offerId) {
                throw new CreatorItemsException(
                    __('Invalid offer id "%1" in received from Allegro API response', $offerId)
                );
            }
            try {
                $product = $this->productRepository->getByAllegroOfferId($offerId);
            } catch (NoSuchEntityException $e) {
                throw new CreatorItemsException(__('Product for requested offer id "%1" does not exist', $offerId));
            }

            $lineItemsIds[$product->getSku()] = $lineItem->getId();
            $product->setPrice($lineItem->getPrice()->getAmount());
            $quote->addProduct($product, $lineItem->getQty());
        }
        return $lineItemsIds;
    }

    /**
     * @param LineItemInterface $lineItem
     * @return bool
     */
    private function isValidLineItem(LineItemInterface $lineItem)
    {
        return $lineItem->getId() != null && $lineItem->getQty() != null && $lineItem->getPrice()->getAmount() != null;
    }

    /**
     * @param Quote $quote
     * @param CheckoutFormInterface $checkoutForm
     */
    private function processShipping(Quote $quote, CheckoutFormInterface $checkoutForm)
    {
        $checkoutForm->getDelivery()->getAddress()->fillAddress($quote->getShippingAddress());
        $quote->getShippingAddress()->setCustomerId($quote->getCustomer()->getId());

        $shippingMethodCode = $this->shipping->getShippingMethodCode($checkoutForm);

        $quote->getShippingAddress()
            ->setShippingMethod($shippingMethodCode)
            ->setCollectShippingRates(true)
            ->collectShippingRates();
    }

    /**
     * @param Quote $quote
     * @param CheckoutFormInterface $checkoutForm
     */
    private function processBilling(Quote $quote, CheckoutFormInterface $checkoutForm)
    {
        $checkoutForm->getInvoice()->getAddress()->fillAddress(
            $quote->getBillingAddress(),
            $checkoutForm->getDelivery()->getAddress()
        );
        $quote->getBillingAddress()->setCustomerId($quote->getCustomer()->getId());

        $quote->getPayment()->setMethod(
            $this->payment->getPaymentMethodCode(
                $checkoutForm
            )
        );
    }

    /**
     * @param Quote $quote
     * @param CheckoutFormInterface $checkoutForm
     * @return \Magento\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function placeOrder(Quote $quote, CheckoutFormInterface $checkoutForm)
    {
        $this->eventManager->dispatch(
            self::EVENT_NAME,
            [
                'checkoutForm' => $checkoutForm,
                'quote' => $quote
            ]
        );

        $quote->collectTotals();

        // TODO use ExtensionAttributesInterface
        $quote->setData('order_from', 'Allegro');
        $quote->setData('external_id', $checkoutForm->getId());

        $quote->collectTotals()->save();
        return $this->quoteManagement->submit($quote);
    }

    /**
     * @return StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStore()
    {
        $storeId = $this->scopeConfig->getValue(self::STORE_ID_CONFIG_KEY);
        if (!$storeId) {
            $storeId = Store::DEFAULT_STORE_ID;
        }

        return $this->storeManager->getStore($storeId);
    }
}
