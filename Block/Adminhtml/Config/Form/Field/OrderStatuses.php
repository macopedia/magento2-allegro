<?php

namespace Macopedia\Allegro\Block\Adminhtml\Config\Form\Field;

use Macopedia\Allegro\Block\Adminhtml\Config\Form\Field\Renderer\AllegroOrderStatuses;
use Macopedia\Allegro\Block\Adminhtml\Config\Form\Field\Renderer\MagentoOrderStatuses;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class OrderStatuses extends AbstractFieldArray
{
    /** @var MagentoOrderStatuses */
    protected $magentoBlockOptions;

    /** @var AllegroOrderStatuses */
    protected $allegroBlockOptions;

    /**
     * @inheritdoc
     */
    protected function _prepareToRender()
    {
        $this->addColumn('allegro_code', [
            'label' => __('Allegro order status'),
            'class' => 'required-entry',
            'renderer' => $this->getAllegroStatusesRenderer()
        ]);
        $this->addColumn('magento_code', [
            'label' => __('Magento order status'),
            'class' => 'required-entry',
            'renderer' => $this->getMagnetoStatusesRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add status');
    }

    /**
     * @return MagentoOrderStatuses
     * @throws LocalizedException
     */
    protected function getMagnetoStatusesRenderer()
    {
        if (!$this->magentoBlockOptions) {
            $this->magentoBlockOptions = $this->getLayout()->createBlock(
                MagentoOrderStatuses::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->magentoBlockOptions;
    }

    /**
     * @return AllegroOrderStatuses
     * @throws LocalizedException
     */
    protected function getAllegroStatusesRenderer()
    {
        if (!$this->allegroBlockOptions) {
            $this->allegroBlockOptions = $this->getLayout()->createBlock(
                AllegroOrderStatuses::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->allegroBlockOptions;
    }

    /**
     * Prepare existing row data object.
     *
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];
        $customAttribute = $row->getData('magento_code');
        $key = 'option_' . $this->getMagnetoStatusesRenderer()->calcOptionHash($customAttribute);
        $options[$key] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);

        $customAttribute = $row->getData('allegro_code');
        $key = 'option_' . $this->getAllegroStatusesRenderer()->calcOptionHash($customAttribute);
        $options[$key] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);
    }
}
