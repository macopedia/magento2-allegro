<?php

namespace Macopedia\Allegro\Api\Data;

use Macopedia\Allegro\Api\Data\CheckoutForm\BuyerInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\DeliveryInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\InvoiceInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\PaymentInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\LineItemInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\SummaryInterface;

interface CheckoutFormInterface
{

    /**
     * @param string $id
     * @return void
     */
    public function setId(string $id);

    /**
     * @param BuyerInterface $buyer
     * @return void
     */
    public function setBuyer(BuyerInterface $buyer);

    /**
     * @param PaymentInterface $payment
     * @return void
     */
    public function setPayment(PaymentInterface $payment);

    /**
     * @param string $status
     * @return void
     */
    public function setStatus(string $status);

    /**
     * @param DeliveryInterface $delivery
     * @return void
     */
    public function setDelivery(DeliveryInterface $delivery);

    /**
     * @param InvoiceInterface $invoice
     * @return void
     */
    public function setInvoice(InvoiceInterface $invoice);

    /**
     * @param LineItemInterface[] $lineItems
     * @return void
     */
    public function setLineItems(array $lineItems);

    /**
     * @param SummaryInterface $summary
     * @return void
     */
    public function setSummary(SummaryInterface $summary);

    /**
     * @param string $messageToSeller
     * @return void
     */
    public function setMessageToSeller(string $messageToSeller);

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @return BuyerInterface
     */
    public function getBuyer(): BuyerInterface;

    /**
     * @return PaymentInterface
     */
    public function getPayment(): PaymentInterface;

    /**
     * @return string
     */
    public function getStatus(): ?string;

    /**
     * @return DeliveryInterface
     */
    public function getDelivery(): DeliveryInterface;

    /**
     * @return InvoiceInterface
     */
    public function getInvoice(): InvoiceInterface;

    /**
     * @return LineItemInterface[]
     */
    public function getLineItems(): array;

    /**
     * @return SummaryInterface
     */
    public function getSummary(): SummaryInterface;

    /**
     * @return string
     */
    public function getMessageToSeller(): ?string;

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData);
}
