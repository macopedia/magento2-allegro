<?php

namespace Macopedia\Allegro\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface ProductRepositoryInterface extends \Magento\Catalog\Api\ProductRepositoryInterface
{

    /**
     * @param int $allegroOfferId
     * @param bool $editMode
     * @param int $storeId
     * @param bool $forceReload
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    public function getByAllegroOfferId(
        int $allegroOfferId,
        bool $editMode = false,
        int $storeId = null,
        bool $forceReload = false
    ): ProductInterface;
}
