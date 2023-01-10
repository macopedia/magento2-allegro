<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\ImageInterface;
use Macopedia\Allegro\Api\ImageRepositoryInterface;
use Macopedia\Allegro\Model\ResourceModel\Sale\Images;
use Magento\Framework\Exception\CouldNotSaveException;

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
     * {@inheritDoc}
     */
    public function save(ImageInterface $image)
    {
        if ($image->getStatus() == ImageInterface::STATUS_UPLOADED) {
            throw new CouldNotSaveException(__(
                'Image with url "%1" is already uploaded to allegro',
                $image->getPath()
            ));
        }

        try {
            $response = $this->images->postImage($image->getPath());
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __('Image with url "%1" could not be saved Reason: %2', $image->getPath(), $e->getMessage()),
                $e
            );
        }

        $image->setUrl($response['location']);
        $image->setStatus(ImageInterface::STATUS_UPLOADED);
    }
}
