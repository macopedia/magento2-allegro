<?php

namespace Macopedia\Allegro\Controller\Adminhtml\Offer;

use Macopedia\Allegro\Api\Data\ImageInterface;
use Macopedia\Allegro\Api\Data\Offer\LocationInterface;
use Macopedia\Allegro\Api\Data\OfferInterface;
use Macopedia\Allegro\Api\Data\ParameterInterface;
use Macopedia\Allegro\Api\Data\ParameterInterfaceFactoryInterface;
use Macopedia\Allegro\Controller\Adminhtml\Offer;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Save controller class
 */
class Save extends Offer
{

    /**
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        try {
            $data = $this->getRequest()->getParam('allegro');

            $product = false;
            $productId = $data['product'] ?? '';
            if ($productId) {
                $product = $this->productRepository->getById($productId);
            }

            $offer = $this->initializeOffer($data);
            $this->offerRepository->save($offer);

            if ($product) {
                $product->setData('allegro_offer_id', $offer->getId());

                try {
                    $this->productRepository->save($product);
                } catch (CouldNotSaveException $e) {
                    $this->messageManager->addWarningMessage(
                        __('Offer saved successfully but can not assign it to product. Please update product with proper offer ID manually')
                    );
                    return $this->createRedirectEditResult($offer->getId());
                }
            }

            $this->messageManager->addSuccessMessage(__('Offer saved successfully'));
            return $this->createRedirectEditResult($offer->getId());

        } catch (LocalizedException $e) {
            $this->logger->critical($e);
            $this->messageManager->addExceptionMessage($e);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(__('Something went wrong'));
        }

        return $this->createRedirectIndexResult();
    }

    /**
     * @param array $data
     * @return OfferInterface
     */
    private function initializeOffer(array $data): OfferInterface
    {
        if (isset($data['id'])) {
            $offer = $this->offerRepository->get($data['id']);
        } else {
            /** @var OfferInterface $offer */
            $offer = $this->offerFactory->create();

            /** @var LocationInterface $location */
            $location = $this->locationFactory->create();
            $location->setCountryCode(
                $this->scopeConfig->getValue('allegro/origin/country_id')
            );
            $location->setProvince(
                $this->scopeConfig->getValue('allegro/origin/province')
            );
            $location->setCity(
                $this->scopeConfig->getValue('allegro/origin/city')
            );
            $location->setPostCode(
                $this->scopeConfig->getValue('allegro/origin/post_code')
            );
            $offer->setLocation($location);
        }

        $offer->setName($data['name']);
        $offer->setCategory($data['category']);
        $offer->setDescription($data['description']);
        $offer->setQty($data['qty']);
        $offer->setPrice($data['price']);
        $offer->setParameters($this->initializeParameters($data));
        $offer->setDeliveryShippingRatesId($data['delivery_shipping_rates_id']);
        $offer->setDeliveryHandlingTime($data['delivery_handling_time']);
        $offer->setPaymentsInvoice($data['payments_invoice']);

        if (isset($data['images'])) {
            $offer->setImages($this->initializeImages($data['images']));
        }

        return $offer;
    }

    /**
     * @param array $data
     * @return ParameterInterface[]
     */
    private function initializeParameters(array $data): array
    {
        if (!$data || !isset($data['category']) || !isset($data['parameters'])) {
            return [];
        }

        $result = [];
        foreach ($this->parameterDefinitionRepository->createParametersByCategoryId($data['category']) as $parameter) {
            if (!isset($data['parameters'][$parameter->getId()])) {
                continue;
            }

            $parameter->setValue($data['parameters'][$parameter->getId()]);
            $result[] = $parameter;
        }

        return $result;
    }

    /**
     * @param array $data
     * @return ImageInterface[]
     */
    private function initializeImages(array $data): array
    {
        $images = [];
        foreach ($data as $imageData) {
            /** @var ImageInterface $image */
            $image = $this->imageFactory->create();
            $image->setUrl($imageData);
            $image->setStatus(ImageInterface::STATUS_LOCAL);
            $images[] = $image;
        }
        return $images;
    }
}
