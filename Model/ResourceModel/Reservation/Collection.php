<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\ResourceModel\Reservation;

use Macopedia\Allegro\Model\Reservation;
use Macopedia\Allegro\Model\ResourceModel\Reservation as Resource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Reservation::class, Resource::class);
    }
}
