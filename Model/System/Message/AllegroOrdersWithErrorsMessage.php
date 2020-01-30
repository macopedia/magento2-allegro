<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Model\System\Message;

use Macopedia\Allegro\Api\OrderLogRepositoryInterface;
use Magento\Framework\Notification\MessageInterface;
use Magento\Framework\Phrase;
use Magento\Framework\UrlInterface;

/**
 * Class responsible for viewing system message about orders that could not be imported
 */
class AllegroOrdersWithErrorsMessage implements MessageInterface
{

    const MESSAGE_IDENTITY = 'allegro_orders_with_errors_message';

    /** @var OrderLogRepositoryInterface */
    private $orderLogRepository;

    /** @var UrlInterface */
    private $urlBuilder;

    /** @var null|int */
    private $count = null;

    /**
     * AllegroOrdersWithErrorsMessage constructor.
     * @param OrderLogRepositoryInterface $orderLogRepository
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        OrderLogRepositoryInterface $orderLogRepository,
        UrlInterface $urlBuilder
    ) {
        $this->orderLogRepository = $orderLogRepository;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Retrieve unique system message identity
     *
     * @return string
     */
    public function getIdentity()
    {
        return self::MESSAGE_IDENTITY;
    }

    /**
     * Check whether the system message should be shown
     *
     * @return bool
     */
    public function isDisplayed()
    {
        if ($this->getCountOfOrders() > 0) {
            return true;
        }
        return false;
    }

    /**
     * Retrieve system message text
     * @return Phrase
     */
    public function getText()
    {
        $url = $this->urlBuilder->getUrl('allegro/orders/');
        $count = $this->getCountOfOrders();

        return __('%1 order/orders could not be imported. You can check details <a href="%2">here</a>', $count, $url);
    }

    /**
     * Retrieve system message severity
     * Possible default system message types:
     * - MessageInterface::SEVERITY_CRITICAL
     * - MessageInterface::SEVERITY_MAJOR
     * - MessageInterface::SEVERITY_MINOR
     * - MessageInterface::SEVERITY_NOTICE
     *
     * @return int
     */
    public function getSeverity()
    {
        return self::SEVERITY_MAJOR;
    }

    /**
     * @return int
     */
    private function getCountOfOrders()
    {
        if ($this->count === null) {
            $this->count = $this->orderLogRepository->getCount();
        }
        return $this->count;
    }
}
