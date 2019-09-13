<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Allegro attributes source model
 */
class AllegroAttribute implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            'id' => 'id',
            'name' => 'name'
        ];
    }
}
