<?php

namespace Macopedia\Allegro\Controller\Adminhtml\Offer;

use Macopedia\Allegro\Controller\Adminhtml\Offer;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Edit controller class
 */
class Create extends Offer
{

    /**
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        try {
            \Magento\Framework\Profiler::start(__CLASS__ . '::' . __METHOD__);
            $productId = $this->getRequest()->getParam('product');
            if (!$productId) {
                throw new LocalizedException(__('Requested product does not exists'));
            }

            $this->credentials->getToken();

            $product = $this->productRepository->getById($productId);

            // TODO use ExtensionAttributesInterface
            $offerId = $product->getData('allegro_offer_id');

            if ($offerId) {
                throw new LocalizedException(__('Offer for requested product already exists'));
            }

            $this->registry->register('product', $product);
            \Magento\Framework\Profiler::stop(__CLASS__ . '::' . __METHOD__);

            return $this->createPageResult();
        } catch (LocalizedException $e) {
            $this->logger->critical($e);
            $this->messageManager->addExceptionMessage($e);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage("Something went wrong");
        }

        return $this->createForwardNoRouteResult();
    }
}
