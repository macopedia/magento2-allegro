<?php

namespace Macopedia\Allegro\Model\Data\CheckoutForm;

use Macopedia\Allegro\Api\Data\CheckoutForm\AmountInterface;
use Macopedia\Allegro\Api\Data\CheckoutForm\AmountInterfaceFactory;
use Macopedia\Allegro\Api\Data\CheckoutForm\LineItemInterface;
use Magento\Framework\DataObject;

class LineItem extends DataObject implements LineItemInterface
{

    const ID_FIELD_NAME = 'id';
    const QTY_FIELD_NAME = 'qty';
    const PRICE_FIELD_NAME = 'price';
    const OFFER_ID_FIELD_NAME = 'offer_id';

    /** @var AmountInterfaceFactory */
    private $amountFactory;

    /**
     * LineItem constructor.
     * @param AmountInterfaceFactory $amountFactory
     */
    public function __construct(AmountInterfaceFactory $amountFactory)
    {
        $this->amountFactory = $amountFactory;
    }

    /**
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->setData(self::ID_FIELD_NAME, $id);
    }

    /**
     * @param float $qty
     */
    public function setQty(float $qty)
    {
        $this->setData(self::QTY_FIELD_NAME, $qty);
    }

    /**
     * @param AmountInterface $price
     */
    public function setPrice(AmountInterface $price)
    {
        $this->setData(self::PRICE_FIELD_NAME, $price);
    }

    /**
     * @param string $offerId
     */
    public function setOfferId(string $offerId)
    {
        $this->setData(self::OFFER_ID_FIELD_NAME, $offerId);
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->getData(self::ID_FIELD_NAME);
    }

    /**
     * @return float|null
     */
    public function getQty(): ?float
    {
        return $this->getData(self::QTY_FIELD_NAME);
    }

    /**
     * @return AmountInterface
     */
    public function getPrice(): AmountInterface
    {
        return $this->getData(self::PRICE_FIELD_NAME);
    }

    /**
     * @return string|null
     */
    public function getOfferId(): ?string
    {
        return $this->getData(self::OFFER_ID_FIELD_NAME);
    }

    /**
     * @param array $rawData
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
}
