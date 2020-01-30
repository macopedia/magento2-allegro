<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Province implements OptionSourceInterface
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
            '' => __('--Please Select--'),
            'DOLNOSLASKIE' => 'Dolnośląskie',
            'KUJAWSKO_POMORSKIE' => 'Kujawsko-pomorskie',
            'LUBELSKIE' => 'Lubelskie',
            'LUBUSKIE' => 'Lubuskie',
            'LODZKIE' => 'Łódzkie',
            'MALOPOLSKIE' => 'Małopolskie',
            'MAZOWIECKIE' => 'Mazowieckie',
            'OPOLSKIE' => 'Opolskie',
            'PODKARPACKIE' => 'Podkarpackie',
            'PODLASKIE' => 'Podlaskie',
            'POMORSKIE' => 'Pomorskie',
            'SLASKIE' => 'Śląskie',
            'SWIETOKRZYSKIE' => 'Świętokrzyskie',
            'WARMINSKO_MAZURSKIE' => 'Warmińsko-mazurskie',
            'WIELKOPOLSKIE' => 'Wielkopolskie',
            'ZACHODNIOPOMORSKIE' => 'Zachodniopomorskie',
        ];
    }
}
