<?php

namespace Macopedia\Allegro\Logger;

use Magento\Framework\Logger\Handler\Base as BaseHandler;

/**
 * ApiHandler for logger class
 */
class ApiHandler extends BaseHandler
{
    protected $loggerType = Logger::INFO;
    protected $fileName = '/var/log/allegro-api.log';

    /**
     * @param array $record
     * @return bool
     */
    public function isHandling(array $record)
    {
        return parent::isHandling($record) && !($record['context'][Logger::IS_EXCEPTION_KEY] ?? false);
    }
}
