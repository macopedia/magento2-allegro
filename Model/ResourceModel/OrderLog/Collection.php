<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Model\ResourceModel\OrderLog;

use Macopedia\Allegro\Model\OrderLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(OrderLog::class, \Macopedia\Allegro\Model\ResourceModel\OrderLog::class);
    }
}