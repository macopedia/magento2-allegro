<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\CheckoutFormInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\BuyerInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\BuyerInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\DeliveryInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\DeliveryInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\InvoiceInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\InvoiceInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\PaymentInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\PaymentInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\LineItemInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\LineItemInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\SummaryInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\SummaryInterfaceFactory;
use Magento\Framework\DataObject;

class CheckoutForm extends DataObject implements CheckoutFormInterface
{

    const ID_FIELD_NAME = 'id';
    const BUYER_FIELD_NAME = 'buyer';
    const PAYMENT_FIELD_NAME = 'payment';
    const STATUS_FIELD_NAME = 'status';
    const DELIVERY_FIELD_NAME = 'delivery';
    const INVOICE_FIELD_NAME = 'invoice';
    const LINE_ITEMS_FIELD_NAME = 'line_items';
    const SUMMARY_FIELD_NAME = 'summary';
    const MESSAGE_TO_SELLER_FIELD_NAME = 'message_to_seller';

    /** @var BuyerInterfaceFactory */
    private $buyerFactory;

    /** @var DeliveryInterfaceFactory */
    private $deliveryFactory;

    /** @var InvoiceInterfaceFactory */
    private $invoiceFactory;

    /** @var PaymentInterfaceFactory */
    private $paymentFactory;

    /** @var LineItemInterfaceFactory */
    private $lineItemFactory;

    /** @var SummaryInterfaceFactory */
    private $summaryFactory;

    /**
     * CheckoutForm constructor.
     * @param BuyerInterfaceFactory $buyerFactory
     * @param DeliveryInterfaceFactory $deliveryFactory
     * @param InvoiceInterfaceFactory $invoiceFactory
     * @param PaymentInterfaceFactory $paymentFactory
     * @param LineItemInterfaceFactory $lineItemFactory
     * @param SummaryInterfaceFactory $summaryFactory
     */
    public function __construct(
        BuyerInterfaceFactory $buyerFactory,
        DeliveryInterfaceFactory $deliveryFactory,
        InvoiceInterfaceFactory $invoiceFactory,
        PaymentInterfaceFactory $paymentFactory,
        LineItemInterfaceFactory $lineItemFactory,
        SummaryInterfaceFactory $summaryFactory
    ) {
        $this->buyerFactory = $buyerFactory;
        $this->deliveryFactory = $deliveryFactory;
        $this->invoiceFactory = $invoiceFactory;
        $this->paymentFactory = $paymentFactory;
        $this->lineItemFactory = $lineItemFactory;
        $this->summaryFactory = $summaryFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function setId(string $id)
    {
        $this->setData(self::ID_FIELD_NAME, $id);
    }

    /**
     * {@inheritDoc}
     */
    public function setBuyer(BuyerInterface $buyer)
    {
        $this->setData(self::BUYER_FIELD_NAME, $buyer);
    }

    /**
     * {@inheritDoc}
     */
    public function setPayment(PaymentInterface $payment)
    {
        $this->setData(self::PAYMENT_FIELD_NAME, $payment);
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus(string $status)
    {
        $this->setData(self::STATUS_FIELD_NAME, $status);
    }

    /**
     * {@inheritDoc}
     */
    public function setDelivery(DeliveryInterface $delivery)
    {
        $this->setData(self::DELIVERY_FIELD_NAME, $delivery);
    }

    /**
     * {@inheritDoc}
     */
    public function setInvoice(InvoiceInterface $invoice)
    {
        $this->setData(self::INVOICE_FIELD_NAME, $invoice);
    }

    /**
     * {@inheritDoc}
     */
    public function setLineItems(array $lineItems)
    {
        $this->setData(self::LINE_ITEMS_FIELD_NAME, $lineItems);
    }

    /**
     * {@inheritDoc}
     */
    public function setSummary(SummaryInterface $summary)
    {
        $this->setData(self::SUMMARY_FIELD_NAME, $summary);
    }

    /**
     * {@inheritDoc}
     */
    public function setMessageToSeller(string $messageToSeller)
    {
        $this->setData(self::MESSAGE_TO_SELLER_FIELD_NAME, $messageToSeller);
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): ?string
    {
        return $this->getData(self::ID_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getBuyer(): BuyerInterface
    {
        return $this->getData(self::BUYER_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getPayment(): PaymentInterface
    {
        return $this->getData(self::PAYMENT_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): ?string
    {
        return $this->getData(self::STATUS_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getDelivery(): DeliveryInterface
    {
        return $this->getData(self::DELIVERY_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getInvoice(): InvoiceInterface
    {
        return $this->getData(self::INVOICE_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getLineItems(): array
    {
        return $this->getData(self::LINE_ITEMS_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getSummary(): SummaryInterface
    {
        return $this->getData(self::SUMMARY_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getMessageToSeller(): ?string
    {
        return $this->getData(self::MESSAGE_TO_SELLER_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
        }
        if (isset($rawData['status'])) {
            $this->setStatus($rawData['status']);
        }
        if (isset($rawData['messageToSeller'])) {
            $this->setMessageToSeller($rawData['messageToSeller']);
        }

        $this->setBuyer($this->mapBuyerData($rawData['buyer'] ?? []));
        $this->setPayment($this->mapPaymentData($rawData['payment'] ?? []));
        $this->setDelivery($this->mapDeliveryData($rawData['delivery'] ?? []));
        $this->setLineItems($this->mapLineItemsData($rawData['lineItems'] ?? []));
        $this->setInvoice($this->mapInvoiceData($rawData['invoice'] ?? []));
        $this->setSummary($this->mapSummaryData($rawData['summary'] ?? []));
    }

    /**
     * @param array $data
     * @return BuyerInterface
     */
    public function mapBuyerData(array $data): BuyerInterface
    {
        /** @var BuyerInterface $buyer */
        $buyer = $this->buyerFactory->create();
        $buyer->setRawData($data);
        return $buyer;
    }

    /**
     * @param array $data
     * @return PaymentInterface
     */
    public function mapPaymentData(array $data): PaymentInterface
    {
        /** @var PaymentInterface $payment */
        $payment = $this->paymentFactory->create();
        $payment->setRawData($data);
        return $payment;
    }

    /**
     * @param array $data
     * @return DeliveryInterface
     */
    public function mapDeliveryData(array $data): DeliveryInterface
    {
        /** @var DeliveryInterface $delivery */
        $delivery = $this->deliveryFactory->create();
        $delivery->setRawData($data);
        return $delivery;
    }

    /**
     * @param array $data
     * @return InvoiceInterface
     */
    public function mapInvoiceData(array $data): InvoiceInterface
    {
        /** @var InvoiceInterface $invoice */
        $invoice = $this->invoiceFactory->create();
        $invoice->setRawData($data);
        return $invoice;
    }

    /**
     * @param array $data
     * @return LineItemInterface[]
     */
    public function mapLineItemsData(array $data): array
    {
        $lineItems = [];
        foreach ($data as $lineItemData) {
            /** @var LineItemInterface $lineItem */
            $lineItem = $this->lineItemFactory->create();
            $lineItem->setRawData($lineItemData);
            $lineItems[] = $lineItem;
        }
        return $lineItems;
    }

    /**
     * @param array $data
     * @return SummaryInterface
     */
    public function mapSummaryData(array $data): SummaryInterface
    {
        /** @var SummaryInterface $summary */
        $summary = $this->summaryFactory->create();
        $summary->setRawData($data);
        return $summary;
    }
}
