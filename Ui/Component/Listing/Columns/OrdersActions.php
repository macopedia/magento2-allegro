<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * OrdersActions Class
 */
class OrdersActions extends Column
{
    /** @var UrlInterface */
    private $urlBuilder;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            $item[$this->getData('name')]['import'] = [
                'href' => $this->getOrdersImportUrl($item['checkout_form_id']),
                'label' => __('Import'),
                'hidden' => false,
                '__disableTmpl' => true
            ];
        }

        return $dataSource;
    }

    /**
     * @param string $checkoutFormId
     * @return string
     */
    private function getOrdersImportUrl(string $checkoutFormId)
    {
        return $this->urlBuilder->getUrl('allegro/orders/import', ['id' => $checkoutFormId]);
    }
}
