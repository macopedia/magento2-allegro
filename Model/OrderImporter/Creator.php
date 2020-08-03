<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\OrderImporter;

use Macopedia\Allegro\Api\Data\CheckoutForm\LineItemInterface;
use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Macopedia\Allegro\Api\ProductRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\LocalizedException;
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
use Magento\Framework\Registry;
use Magento\Quote\Api\Data\CartExtensionFactory;

/**
 * Magento order creator
 */
class Creator
{
    const STORE_ID_CONFIG_KEY = 'allegro/order/store';
    const EVENT_NAME = 'allegro_order_import_before_quote_save';

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

    /** @var ManagerInterface */
    protected $eventManager;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var StoreManagerInterface */
    private $storeManager;

    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var QuoteManagement */
    private $quoteManagement;

    /** @var Registry */
    private $registry;

    /** @var CartExtensionFactory */
    private $cartExtensionFactory;

    /**
     * Creator constructor.
     * @param Shipping $shipping
     * @param Payment $payment
     * @param Status $status
     * @param Invoice $invoice
     * @param OrderRepositoryInterface $orderRepository
     * @param QuoteFactory $quoteFactory
     * @param ManagerInterface $eventManager
     * @param CartExtensionFactory $cartExtensionFactory
     * @param ProductRepositoryInterface $productRepository
     * @param StoreManagerInterface $storeManager
     * @param ScopeConfigInterface $scopeConfig
     * @param QuoteManagement $quoteManagement
     * @param Registry $registry
     */
    public function __construct(
        Shipping $shipping,
        Payment $payment,
        Status $status,
        Invoice $invoice,
        OrderRepositoryInterface $orderRepository,
        QuoteFactory $quoteFactory,
        ManagerInterface $eventManager,
        CartExtensionFactory $cartExtensionFactory,
        ProductRepositoryInterface $productRepository,
        StoreManagerInterface $storeManager,
        ScopeConfigInterface $scopeConfig,
        QuoteManagement $quoteManagement,
        Registry $registry
    ) {
        $this->shipping = $shipping;
        $this->payment = $payment;
        $this->status = $status;
        $this->invoice = $invoice;
        $this->orderRepository = $orderRepository;
        $this->quoteFactory = $quoteFactory;
        $this->eventManager = $eventManager;
        $this->cartExtensionFactory = $cartExtensionFactory;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->quoteManagement = $quoteManagement;
        $this->registry = $registry;
    }

    /**
     * @param CheckoutFormInterface $checkoutForm
     * @return bool
     * @throws CreatorItemsException
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function execute(CheckoutFormInterface $checkoutForm)
    {
        $this->registry->register('is_allegro_order', true, true);
        /** @var Quote $quote */
        $quote = $this->quoteFactory->create();
        $quote->setStore($this->getStore());
        $quote->setStoreId($this->getStore()->getId());

        $cartExtension = $quote->getExtensionAttributes();
        if ($cartExtension === null) {
            $cartExtension = $this->cartExtensionFactory->create();
        }
        $cartExtension->setExternalId($checkoutForm->getId());
        $cartExtension->setOrderFrom(OriginOfOrder::ALLEGRO);
        $cartExtension->setAllegroBuyerLogin($checkoutForm->getBuyer()->getLogin());
        $quote->setExtensionAttributes($cartExtension);

        $quote->setAllegroShippingPrice($checkoutForm->getDelivery()->getCost()->getAmount());

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

        $this->processStatus($order, $checkoutForm);
        $this->processComments($order, $checkoutForm);

        $checkoutForm->getDelivery()->getPickupPoint()->fillOrder($order);

        $this->orderRepository->save($order);

        return $order->getId();
    }

    /**
     * @param Quote $quote
     * @param CheckoutFormInterface $checkoutForm
     */
    private function processCustomer(Quote $quote, CheckoutFormInterface $checkoutForm)
    {
        if (!$checkoutForm->getBuyer()->getFirstName()) {
            $checkoutForm->getBuyer()->setFirstName(
                $checkoutForm->getDelivery()->getAddress()->getFirstName()
            );
        }
        if (!$checkoutForm->getBuyer()->getLastName()) {
            $checkoutForm->getBuyer()->setLastName(
                $checkoutForm->getDelivery()->getAddress()->getLastName()
            );
        }

        $quote->setCustomerFirstname($checkoutForm->getBuyer()->getFirstName());
        $quote->setCustomerLastname($checkoutForm->getBuyer()->getLastName());
        $quote->setCustomerEmail($checkoutForm->getBuyer()->getEmail());
        $quote->setCustomerIsGuest(true);
    }

    /**
     * @param Quote $quote
     * @param CheckoutFormInterface $checkoutForm
     * @return array
     * @throws CreatorItemsException
     * @throws LocalizedException
     */
    private function processItems(Quote $quote, CheckoutFormInterface $checkoutForm)
    {
        $lineItemsIds = [];
        foreach ($checkoutForm->getLineItems() as $lineItem) {
            if (!$this->isValidLineItem($lineItem)) {
                throw new CreatorItemsException('Invalid item data in received from Allegro API response');
            }

            $offerId = (int)$lineItem->getOfferId();
            if (!$offerId) {
                throw new CreatorItemsException("Invalid offer id {$offerId} in received from Allegro API response");
            }
            try {
                $product = $this->productRepository->getByAllegroOfferId($offerId, false, null, true);
            } catch (NoSuchEntityException $e) {
                throw new CreatorItemsException("Product for requested offer id {$offerId} does not exist");
            }

            $lineItemsIds[$product->getSku()] = $lineItem->getId();
            $product->setPrice($lineItem->getPrice()->getAmount());
            $product->setSpecialPrice(null);
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
    public function processShipping(Quote $quote, CheckoutFormInterface $checkoutForm)
    {
        $checkoutForm->getDelivery()->getAddress()->fillAddress($quote->getShippingAddress());
        $quote->getShippingAddress()->setCustomerId($quote->getCustomer()->getId());

        $shippingMethodCode = $this->shipping->getShippingMethodCode($checkoutForm);

        $quote->getShippingAddress()
            ->setShippingMethod($shippingMethodCode)
            ->setBaseDiscountAmount(0)
            ->setDiscountAmount(0)
            ->setAllegroShippingPrice($checkoutForm->getDelivery()->getCost()->getAmount())
            ->setCollectShippingRates(true)
            ->collectShippingRates();
    }

    /**
     * @param Quote $quote
     * @param CheckoutFormInterface $checkoutForm
     */
    public function processBilling(Quote $quote, CheckoutFormInterface $checkoutForm)
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
     * @return OrderInterface
     * @throws LocalizedException
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

        $quote->collectTotals()->save();
        return $this->quoteManagement->submit($quote);
    }

    /**
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    private function getStore()
    {
        $storeId = $this->scopeConfig->getValue(self::STORE_ID_CONFIG_KEY);
        if (!$storeId) {
            $storeId = Store::DEFAULT_STORE_ID;
        }

        return $this->storeManager->getStore($storeId);
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
