<?php

namespace Macopedia\Allegro\Block\Adminhtml\Config\Form\Field\Renderer;

use Magento\Framework\View\Element\Html\Select;

class AllegroOrderStatuses extends Select
{
    /**
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * @return array
     */
    public function getAllegroOrderStatuses()
    {
        return [
            'NEW' => __('NEW'),
            'PROCESSING' => __('PROCESSING'),
            'READY_FOR_SHIPMENT' => __('READY FOR SHIPMENT'),
            'READY_FOR_PICKUP' => __('READY FOR PICKUP'),
            'SENT' => __('SENT'),
            'PICKED_UP' => __('PICKED UP'),
            'CANCELLED' => __('CANCELLED')
        ];
    }
    /**
     * @return Select
     */
    protected function _prepareLayout()
    {
        $this->setOptions(
            $this->getAllegroOrderStatuses()
        );
        return parent::_prepareLayout();
    }
}
