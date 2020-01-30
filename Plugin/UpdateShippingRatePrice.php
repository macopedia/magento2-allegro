<?php

namespace Macopedia\Allegro\Plugin;

use Magento\Quote\Model\Quote;
use Magento\Shipping\Model\Shipping as Subject;
use Magento\Quote\Model\Quote\Address\RateRequest;

class UpdateShippingRatePrice
{

    /**
     * @param Subject $subject
     * @param \Closure $proceed
     * @param RateRequest $request
     * @return mixed
     */
    public function aroundCollectRates(Subject $subject, \Closure $proceed, RateRequest $request)
    {
        /** @var Subject $rateCollector */
        $result = $proceed($request);

        $allItems = $request->getAllItems();
        if (count($allItems) < 1) {
            return $result;
        }

        /** @var Quote $quote */
        $quote = reset($allItems)->getQuote();
        if (!$quote->getExtensionAttributes()->getOrderFrom() || !$quote->getExtensionAttributes()->getExternalId() || !$quote->hasAllegroShippingPrice()) {
            return $result;
        }

        $shippingMethod = $quote->getShippingAddress()->getShippingMethod();
        $allegroShippingPrice = $quote->getAllegroShippingPrice();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $item */
        foreach ($result->getResult()->getAllRates() as $item) {
            if ($item->getCarrier() . '_' . $item->getMethod() !== $shippingMethod) {
                continue;
            }
            $item->setPrice($allegroShippingPrice);
            break;
        }

        return $result;
    }
}
