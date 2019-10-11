<?php

namespace Macopedia\Allegro\Model\ResourceModel\Sale;

use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\ResourceModel\AbstractResource;

/**
 * Resource model to post Images to Allegro API
 */
class Images extends AbstractResource
{
    /**
     * @param string $imageUrl
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function postImage($imagePath)
    {
        return $this->sendRawRequest('sale/images', self::REQUEST_POST, \file_get_contents($imagePath), \mime_content_type($imagePath));
    }
}
