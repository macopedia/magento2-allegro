<?php

namespace Macopedia\Allegro\Controller\Adminhtml\Offer;

use Macopedia\Allegro\Api\Data\PublicationCommandInterface;
use Macopedia\Allegro\Controller\Adminhtml\Offer;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save controller class
 */
class Publish extends Offer
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

            $offer = $this->offerRepository->get($offerId);
            if (!$offer->canBePublished()) {
                throw new LocalizedException(__('Can not publish active or activating offers'));
            }

            /** @var PublicationCommandInterface $publicationCommand */
            $publicationCommand = $this->publicationCommandFactory->create();
            $publicationCommand->setOfferId($offerId);
            $publicationCommand->setAction(PublicationCommandInterface::ACTION_ACTIVATE);
            $this->publicationCommandRepository->save($publicationCommand);

            $this->messageManager->addSuccessMessage(__('Offer published successfully'));
            return $this->createRedirectEditResult($offerId);

        } catch (LocalizedException $e) {
            $this->logger->critical($e);
            $this->messageManager->addExceptionMessage($e);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('Something went wrong'));
        }

        return $this->createRedirectIndexResult();
    }
}
