<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\AmountInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\AmountInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\LineItemInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

class LineItem extends DataObject implements LineItemInterface
{
    const ID_FIELD_NAME = 'id';
    const QTY_FIELD_NAME = 'qty';
    const PRICE_FIELD_NAME = 'price';
    const OFFER_ID_FIELD_NAME = 'offer_id';
    const BOUGHT_AT_FIELD_NAME = 'bought_at';

    /** @var AmountInterfaceFactory */
    private $amountFactory;

    /** @var DateTimeFactory */
    private $dateTimeFactory;

    /**
     * LineItem constructor.
     * @param AmountInterfaceFactory $amountFactory
     * @param DateTimeFactory $dateTimeFactory
     * @param array $data
     */
    public function __construct(
        AmountInterfaceFactory $amountFactory,
        DateTimeFactory $dateTimeFactory,
        array $data = []
    ) {
        parent::__construct($data);
        $this->amountFactory = $amountFactory;
        $this->dateTimeFactory = $dateTimeFactory;
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
    public function setQty(float $qty)
    {
        $this->setData(self::QTY_FIELD_NAME, $qty);
    }

    /**
     * {@inheritDoc}
     */
    public function setPrice(AmountInterface $price)
    {
        $this->setData(self::PRICE_FIELD_NAME, $price);
    }

    /**
     * {@inheritDoc}
     */
    public function setOfferId(string $offerId)
    {
        $this->setData(self::OFFER_ID_FIELD_NAME, $offerId);
    }

    /**
     * {@inheritDoc}
     */
    public function setBoughtAt(int $time)
    {
        $this->setData(self::BOUGHT_AT_FIELD_NAME, $time);
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
    public function getQty(): ?float
    {
        return $this->getData(self::QTY_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrice(): AmountInterface
    {
        return $this->getData(self::PRICE_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getOfferId(): ?string
    {
        return $this->getData(self::OFFER_ID_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getBoughtAt(): int
    {
        return $this->getData(self::BOUGHT_AT_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        if (isset($rawData['id'])) {
            $this->setId($rawData['id']);
        }
        if (isset($rawData['quantity'])) {
            $this->setQty($rawData['quantity']);
        }
        if (isset($rawData['offer']['id'])) {
            $this->setOfferId($rawData['offer']['id']);
        }
        if (isset($rawData['boughtAt'])) {
            $this->setBoughtAt($this->mapBoughtAtData($rawData['boughtAt']));
        }
        $this->setPrice($this->mapAmountData($rawData['price'] ?? []));
    }

    /**
     * @param array $data
     * @return AmountInterface|null
     */
    private function mapAmountData(array $data): AmountInterface
    {
        /** @var AmountInterface $amount */
        $amount = $this->amountFactory->create();
        $amount->setRawData($data);
        return $amount;
    }

    /**
     * @param $boughtAt
     * @return int
     */
    private function mapBoughtAtData($boughtAt)
    {
        /** @var DateTime $dateTime */
        $dateTime = $this->dateTimeFactory->create();
        return $dateTime->timestamp($boughtAt);
    }
}
