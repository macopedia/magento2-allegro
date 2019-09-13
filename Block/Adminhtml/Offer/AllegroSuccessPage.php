<?php

namespace Macopedia\Allegro\Block\Adminhtml\Offer;

use Magento\Backend\Block\Template;

/**
 * Allegro success page block
 */
class AllegroSuccessPage extends Template
{
    /**
     * @return \Magento\Framework\Phrase
     */
    public function getSuccessMessage()
    {
        return __('The offer has been successfully created');
    }
}
