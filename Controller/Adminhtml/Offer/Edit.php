<?php

namespace Macopedia\Allegro\Controller\Adminhtml\Offer;

use Macopedia\Allegro\Controller\Adminhtml\Offer;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Edit controller class
 */
class Edit extends Offer
{

    /**
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        try {
            $offerId = $this->getRequest()->getParam('id');
            if (!$offerId) {
                throw new LocalizedException(__('Requested offer does not exists'));
            }

            $this->credentials->getToken();

            $offer = $this->offerRepository->get($offerId);
            $product = $this->productRepository->getByAllegroOfferId($offer['id']);

            $this->registry->register('offer', $offer);
            $this->registry->register('product', $product);

            return $this->createPageResult();
        } catch (LocalizedException $e) {
            $this->logger->critical($e);
            $this->messageManager->addExceptionMessage($e);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('Something went wrong'));
        }

        return $this->createForwardNoRouteResult();
    }
}
