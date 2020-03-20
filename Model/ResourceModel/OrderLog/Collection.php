<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\ResourceModel\OrderLog;

use Macopedia\Allegro\Model\OrderLog;
use Macopedia\Allegro\Model\ResourceModel\OrderLog as Resource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(OrderLog::class, Resource::class);
    }
}
