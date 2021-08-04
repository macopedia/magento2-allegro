<?php

namespace Macopedia\Allegro\Api\Consumer;

interface MessageInterface
{
    /**
     * @param int $productId
     * @return void
     */
    public function setProductId(int $productId);

    /**
     * @return int|null
     */
    public function getProductId(): ?int;
}
