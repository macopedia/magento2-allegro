<?php

namespace Macopedia\Allegro\Cron;

use Macopedia\Allegro\Logger\Logger;
use Macopedia\Allegro\Model\OrderImporter;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class responsible for importing orders from Allegro API
 */
class RefreshToken
{
    /**
     * @var \Macopedia\Allegro\Model\Api\TokenProvider
     */
    protected $tokenProvider;

    /**
     * RefreshToken constructor.
     * @param \Macopedia\Allegro\Model\Api\TokenProvider $tokenProvider
     */
    public function __construct(
        \Macopedia\Allegro\Model\Api\TokenProvider $tokenProvider
    ) {
        $this->tokenProvider = $tokenProvider;
    }

    /**
     * @throws \Exception
     */
    public function execute()
    {
        $this->tokenProvider->forceRefreshToken();
    }
}
