<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Consumer\MessageInterface;

interface ConsumerInterface
{
    /**
     * @param MessageInterface $message
     * @return void
     */
    public function processMessage(MessageInterface $message);
}
