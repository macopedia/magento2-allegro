<?php

namespace Macopedia\Allegro\Block\Adminhtml\System;

use Macopedia\Allegro\Model\Api\Auth;
use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\Api\TokenProvider;
use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class responsible for authentication with Allegro API
 */
class Authenticate extends Field
{
    /** @var ScopeConfigInterface */
    private $scopeConfig;

    /** @var TokenProvider */
    private $tokenProvider;

    /** @var Auth */
    private $auth;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param TokenProvider $tokenProvider
     * @param Auth $auth
     * @param array $data
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        TokenProvider $tokenProvider,
        Auth $auth,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->tokenProvider = $tokenProvider;
        $this->auth = $auth;
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     * @throws \Exception
     */
    public function render(AbstractElement $element)
    {
        try {

            $token = $this->tokenProvider->getCurrent();
            $statusLabel = '<span style="color: green;">' . __('Active') . '</span>';
            $buttonLabel = __('Connect with another Allegro account');
            $color = '#e5efe5';

        } catch (ClientException $e) {

            $statusLabel = '<span style="color: red;">' . __('Not active') . '</span>';
            $buttonLabel = __('Connect with Allegro account');
            $color = '#fae5e5';

        }

        $html = '<div style="background-color: '.$color.'; padding: 20px 30px;">' . __('Connection status:') . ' ' . $statusLabel . '</div>'
              . '<a href="' . $this->auth->getAuthUrl() . '"><button type="button">' . $buttonLabel . '</button></a>';

        return $html;
    }
}
