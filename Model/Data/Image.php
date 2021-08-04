<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\ImageInterface;
use Magento\Framework\DataObject;

class Image extends DataObject implements ImageInterface
{
    const PATH = 'path';
    const URL_FIELD_NAME = 'url';
    const STATUS_FIELD_NAME = 'status';

    /**
     * {@inheritDoc}
     */
    public function setUrl(string $url)
    {
        $this->setData(self::URL_FIELD_NAME, $url);
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus(int $status)
    {
        $this->setData(self::STATUS_FIELD_NAME, $status);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(): ?string
    {
        return $this->getData(self::URL_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus(): ?int
    {
        return $this->getData(self::STATUS_FIELD_NAME);
    }

    /**
     * {@inheritDoc}
     */
    public function setRawData(array $rawData)
    {
        $this->setData($rawData);
        $this->setStatus(self::STATUS_UPLOADED);
    }

    /**
     * {@inheritDoc}
     */
    public function getRawData(): array
    {
        return $this->getData();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->getData(self::PATH);
    }

    /**
     * @param string $path
     * @return Image
     */
    public function setPath(string $path)
    {
        return $this->setData(self::PATH, $path);
    }
}
