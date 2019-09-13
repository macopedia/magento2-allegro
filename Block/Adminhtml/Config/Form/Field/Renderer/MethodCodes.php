<?php

namespace Macopedia\Allegro\Block\Adminhtml\Config\Form\Field\Renderer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Shipping\Model\Config;
use Magento\Store\Model\ScopeInterface;

/**
 * MethodCodes code class
 */
class MethodCodes extends Select
{
    /** @var ScopeConfigInterface */
    protected $scopeConfig;

    /** @var Config */
    protected $shippingConfig;

    /**
     * MethodCodes constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $shippingConfig
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Config $shippingConfig,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->scopeConfig = $scopeConfig;
        $this->shippingConfig = $shippingConfig;
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
        $carriers = $this->shippingConfig->getActiveCarriers();
        foreach ($carriers as $carrierCode => $carrierModel) {
            $carrierMethods = $carrierModel->getAllowedMethods();
            if (!$carrierMethods) {
                continue;
            }
            $carrierTitle = $this->_scopeConfig->getValue(
                'carriers/' . $carrierCode . '/title',
                ScopeInterface::SCOPE_STORE
            );

            $options = [];
            foreach ($carrierMethods as $methodCode => $methodTitle) {
                $options[] = [
                    'value' => $carrierCode . '_' . $methodCode,
                    'label' => '[' . $carrierCode . '] ' . $methodTitle
                ];
            }
            $this->addOption($options, $carrierTitle);
        }

        return parent::_prepareLayout();
    }
}
