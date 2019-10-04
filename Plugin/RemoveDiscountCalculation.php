<?php
/**
 * Created by PhpStorm.
 * User: maksymilian
 * Date: 04.10.19
 * Time: 13:49
 */

namespace Macopedia\Allegro\Plugin;

use Magento\Quote\Model\Quote\TotalsCollectorList as Subject;
use Magento\Framework\Registry;

class RemoveDiscountCalculation
{
    /** @var Registry */
    private $registry;

    public function __construct(
        Registry $registry
    ) {
        $this->registry = $registry;
    }

    public function aroundGetCollectors(Subject $subject, \Closure $proceed, $storeId)
    {
        $result = $proceed($storeId);

        if (!$this->registry->registry('is_allegro_order')) {
            return $result;
        }

        unset($result['discount'], $result['shipping_discount']);

        return $result;
    }
}
