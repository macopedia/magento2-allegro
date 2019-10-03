<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\WarrantyInterface;

interface WarrantyRepositoryInterface
{

    /**
     * @return WarrantyInterface[]
     */
    public function getList(): array;
}
