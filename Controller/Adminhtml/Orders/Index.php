<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Controller\Adminhtml\Orders;

use Magento\Backend\App\Action;

/**
 * Index controller class
 */
class Index extends Action
{

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Orders with errors'));
        return $resultPage;
    }
}
