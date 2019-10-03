<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\ImpliedWarrantyInterface;

interface ImpliedWarrantyRepositoryInterface
{

    /**
     * @return ImpliedWarrantyInterface[]
     */
    public function getList(): array;
}
