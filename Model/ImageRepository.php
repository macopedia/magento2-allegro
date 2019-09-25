<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\ImageInterface;
use Macopedia\Allegro\Api\ImageRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\ResourceModel\Sale\Images;
use Magento\Framework\Exception\CouldNotSaveException;
use Macopedia\Allegro\Model\Api\ClientException;

class ImageRepository implements ImageRepositoryInterface
{

    /** @var Images */
    private $images;

    /**
     * ImageRepository constructor.
     * @param Images $images
     */
    public function __construct(Images $images)
    {
        $this->images = $images;
    }

    /**
     * @param ImageInterface $image
     * @throws ClientException
     * @throws CouldNotSaveException
     */
    public function save(ImageInterface $image)
    {
        if ($image->getStatus() == ImageInterface::STATUS_UPLOADED) {
            throw new CouldNotSaveException(__('Image with url "%1" is already uploaded to allegro', $image->getUrl()));
        }

        try {
            $response = $this->images->postImage($image->getRawData());
        } catch (ClientResponseException $e) {
            throw new CouldNotSaveException(__('Image with url "%1" could not be saved Reason: %2', $image->getUrl(), $e->getMessage()), $e);
        }

        $image->setUrl($response['location']);
        $image->setStatus(ImageInterface::STATUS_UPLOADED);
    }
}
