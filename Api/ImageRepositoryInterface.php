<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\ImageInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\Exception\CouldNotSaveException;

interface ImageRepositoryInterface
{

    /**
     * @param ImageInterface $image
     * @throws ClientException
     * @throws CouldNotSaveException
     */
    public function save(ImageInterface $image);
}
