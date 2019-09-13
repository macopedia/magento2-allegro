<?php

namespace Macopedia\Allegro\Block\Adminhtml\Config\Form\Field;

use Macopedia\Allegro\Block\Adminhtml\Config\Form\Field\Renderer\AllegroDeliveryMethods;
use Macopedia\Allegro\Block\Adminhtml\Config\Form\Field\Renderer\MethodCodes;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;

/**
 * Class DeliveryMethods
 */
class DeliveryMethods extends AbstractFieldArray
{
    /** @var MethodCodes */
    private $methodCodesBlockOptions;

    /** @var AllegroDeliveryMethods */
    private $allegroDeliveryBlockOptions;

    /**
     * @inheritdoc
     */
    protected function _prepareToRender()
    {
        $this->addColumn('allegro_code', [
            'label' => __('Allegro method name'),
            'class' => 'required-entry',
            'renderer' => $this->getDeliveryMethodsRenderer()
        ]);
        $this->addColumn('magento_code', [
            'label' => __('Shipping method in Magento'),
            'class' => 'required-entry',
            'renderer' => $this->getMethodCodesRenderer()
        ]);

        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add Method');
    }

    /**
     * @return MethodCodes
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getMethodCodesRenderer()
    {
        if (!$this->methodCodesBlockOptions) {
            $this->methodCodesBlockOptions = $this->getLayout()->createBlock(
                MethodCodes::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->methodCodesBlockOptions;
    }

    /**
     * @return AllegroDeliveryMethods
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function getDeliveryMethodsRenderer()
    {
        if (!$this->allegroDeliveryBlockOptions) {
            $this->allegroDeliveryBlockOptions = $this->getLayout()->createBlock(
                AllegroDeliveryMethods::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }

        return $this->allegroDeliveryBlockOptions;
    }

    /**
     * Prepare existing row data object.
     *
     * @param DataObject $row
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];
        $customAttribute = $row->getData('magento_code');
        $key = 'option_' . $this->getMethodCodesRenderer()->calcOptionHash($customAttribute);
        $options[$key] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);

        $customAttribute = $row->getData('allegro_code');
        $key = 'option_' . $this->getDeliveryMethodsRenderer()->calcOptionHash($customAttribute);
        $options[$key] = 'selected="selected"';
        $row->setData('option_extra_attrs', $options);
    }
}
