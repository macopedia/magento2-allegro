<?php

namespace Macopedia\Allegro\Model;

use Magento\Framework\Model\AbstractModel;
use Macopedia\Allegro\Model\ResourceModel\PickupPoint as ResourceModel;

class PickupPoint extends AbstractModel
{

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
