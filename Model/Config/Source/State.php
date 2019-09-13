<?php

namespace Macopedia\Allegro\Model\Config\Source;

use Macopedia\Allegro\Model\OrderImporter\Status;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\Order\Config;

/**
 * Order state source model
 */
class State implements OptionSourceInterface
{
    /** @var Config */
    private $orderConfig;

    /**
     * @param Config $orderConfig
     */
    public function __construct(Config $orderConfig)
    {
        $this->orderConfig = $orderConfig;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $states = $this->orderConfig->getStates();
        $options = [];
        foreach ($states as $state => $status) {
            $options[] = [
                'value' => $status . Status::STATUS_STATE_SEPARATOR . $state,
                'label' => $state
            ];
        }

        return $options;
    }
}
