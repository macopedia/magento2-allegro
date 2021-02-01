<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Model\Config\Source;

use Macopedia\Allegro\Model\OrderImporter\Status;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;

/**
 * Order state source model
 */
class State implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $orderStatusCollectionFactory;

    /**
     * @param CollectionFactory $orderStatusCollectionFactory
     */
    public function __construct(CollectionFactory $orderStatusCollectionFactory)
    {
        $this->orderStatusCollectionFactory = $orderStatusCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->orderStatusCollectionFactory->create()->joinStates();
        $options = [];
        foreach ($collection as $item) {
            $options[] = [
                'value' => $item->getStatus() . Status::STATUS_STATE_SEPARATOR . $item->getState(),
                'label' => __($item->getLabel())
            ];
        }

        return $options;
    }
}
