<?php

namespace Macopedia\Allegro\Model\Consumer;

use Macopedia\Allegro\Api\Consumer\MessageInterface;

/**
 * Message class
 */
class Message implements MessageInterface
{
    /**
     * @var int
     */
    protected $productId;

    /**
     * {@inheritdoc}
     */
    public function setProductId(int $productId)
    {
        return $this->productId = $productId;
    }

    /**
     * {@inheritdoc}
     */
    public function getProductId(): int
    {
        return $this->productId;
    }
}
