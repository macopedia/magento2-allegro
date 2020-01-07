<?php

declare(strict_types=1);

namespace Macopedia\Allegro\Logger;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\Logger\Handler\Base as BaseHandler;

/**
 * HttpRequestHandler for logger class
 */
class HttpRequestHandler extends BaseHandler
{
    protected $loggerType = Logger::DEBUG;
    protected $fileName = '/var/log/allegro-http-request.log';

    /**
     * @param array $record
     * @return bool
     */
    public function isHandling(array $record)
    {
        return parent::isHandling($record) && !($record['context'][Logger::IS_EXCEPTION_KEY] ?? false);
    }
}
