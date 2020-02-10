<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class PaymentsInvoice implements OptionSourceInterface
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
            'VAT' => __('Invoice'),
            'VAT_MARGIN' => __('Invoice Margin'),
            'WITHOUT_VAT' => __('Invoice without VAT'),
            'NO_INVOICE' => __('I don\'t issue invoices')
        ];
    }
}
