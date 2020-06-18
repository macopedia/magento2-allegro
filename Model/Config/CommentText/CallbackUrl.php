<?php
/**
 * Copyright © Macopedia. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Macopedia\Allegro\Model\Config\CommentText;

use Magento\Config\Model\Config\CommentInterface;

class CallbackUrl implements CommentInterface
{
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $url;

    /**
     * CallbackUrl constructor.
     * @param \Magento\Backend\Model\UrlInterface $url
     */
    public function __construct(\Magento\Backend\Model\UrlInterface $url)
    {
        $this->url = $url;
    }

    public function getCommentText($elementValue)
    {
        $this->url->setNoSecret(true);
        return $this->url->getUrl('allegro/system/authenticate');
    }
}
