<?php

namespace Macopedia\Allegro\Logger;

/**
 * Logger for allegro integration debugging
 */
class Logger extends \Monolog\Logger
{
    const IS_EXCEPTION_KEY = 'exception';

    public function exception(\Exception $exception, $message = false)
    {
        $this->error($exception->getMessage(), [self::IS_EXCEPTION_KEY => true, 'exception' => $exception]);
        if ($message) {
            $this->error($message, [self::IS_EXCEPTION_KEY => false]);
        }
    }
}
