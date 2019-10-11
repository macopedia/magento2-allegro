<?php
/**
 * Copyright © Macopedia. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Macopedia\Allegro\Model\Cache;

use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;

/**
 * Cache type declaration for persistent storage of Allegro data.
 */
class Type extends TagScope
{
    const TYPE_IDENTIFIER = 'allegro';
    const CACHE_TAG       = 'ALLEGRO';

    /**
     * @param FrontendPool $cacheFrontendPool
     */
    public function __construct(FrontendPool $cacheFrontendPool)
    {
        parent::__construct($cacheFrontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
    }
}

