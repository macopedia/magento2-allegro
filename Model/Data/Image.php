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
     * @param string $url
     * @return void
     */
    public function setUrl(string $url)
    {
        $this->setData(self::URL_FIELD_NAME, $url);
    }

    /**
     * @param int $status
     * @return void
     */
    public function setStatus(int $status)
    {
        $this->setData(self::STATUS_FIELD_NAME, $status);
    }

    /**
     * @return string
     */
    public function getUrl(): ?string
    {
        return $this->getData(self::URL_FIELD_NAME);
    }

    /**
     * @return string
     */
    public function getStatus(): ?int
    {
        return $this->getData(self::STATUS_FIELD_NAME);
    }

    /**
     * @param array $rawData
     * @return void
     */
    public function setRawData(array $rawData)
    {
        $this->setData($rawData);
        $this->setStatus(self::STATUS_UPLOADED);
    }

    /**
     * @return array
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
    public function setPath($path)
    {
        return $this->setData(self::PATH, $path);
    }
}
