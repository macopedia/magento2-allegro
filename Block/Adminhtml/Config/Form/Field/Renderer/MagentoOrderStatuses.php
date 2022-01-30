<?php

namespace Macopedia\Allegro\Block\Adminhtml\Config\Form\Field\Renderer;

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;

class MagentoOrderStatuses extends Select
{
    /**
     * @var CollectionFactory
     */
    protected $orderStatusCollectionFactory;

    /**
     * @param CollectionFactory $orderStatusCollectionFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        CollectionFactory $orderStatusCollectionFactory,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->orderStatusCollectionFactory = $orderStatusCollectionFactory;
    }

    /**
     * @param $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $collection = $this->orderStatusCollectionFactory->create()->joinStates();
        foreach ($collection as $item) {
            $this->addOption($item->getStatus(), __($item->getLabel()));
        }

        return parent::_prepareLayout();
    }
}
