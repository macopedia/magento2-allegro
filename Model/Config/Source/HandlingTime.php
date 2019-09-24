<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class HandlingTime implements OptionSourceInterface
{

    public function toOptionArray()
    {
        $options = [];
        foreach ($this->getOptions() as $value => $label) {
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }
        return $options;
    }

    public function getOptions()
    {
        return [
            'PT0S' => __('Instantly'),
            'PT24H' => __('%1 hours', 24),
            'PT48H' => __('%1 days', 2),
            'PT72H' => __('%1 days', 3),
            'PT96H' => __('%1 days', 4),
            'PT120H' => __('%1 days', 5),
            'PT144H' => __('%1 days', 7),
            'PT240H' => __('%1 days', 10),
            'PT336H' => __('%1 days', 14),
            'PT576H' => __('%1 days', 21),
            'PT720H' => __('%1 days', 30),
            'PT1440H' => __('%1 days', 60),
        ];
    }

}
