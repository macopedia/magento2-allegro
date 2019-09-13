<?php

namespace Macopedia\Allegro\Logger;

use Magento\Framework\Logger\Handler\Base as BaseHandler;

/**
 * ExceptionHandler for logger class
 */
class ExceptionHandler extends BaseHandler
{
    protected $loggerType = Logger::ERROR;
    protected $fileName = '/var/log/allegro-exceptions.log';

    /**
     * @param array $record
     * @return bool
     */
    public function isHandling(array $record)
    {
        return parent::isHandling($record) && ($record['context'][Logger::IS_EXCEPTION_KEY] ?? true);
    }
}
