<?php

namespace Macopedia\Allegro\Model\ResourceModel\Sale;

use Macopedia\Allegro\Model\Api\ClientException;
use Macopedia\Allegro\Model\ResourceModel\AbstractResource;

/**
 * Resource model to get categories and params from Allegro API
 */
class Categories extends AbstractResource
{

    /**
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getRootList()
    {
        $response = $this->requestGet('/sale/categories');
        return $response['categories'] ??[];
    }

    /**
     * @param string $parentId
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getList($parentId)
    {
        $response = $this->requestGet('/sale/categories?parent.id=' . $parentId);
        return $response['categories'] ?? [];
    }

    /**
     * @param string $categoryId
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function get($categoryId)
    {
        return  $this->requestGet('/sale/categories/' . $categoryId);
    }

    /**
     * @param string $categoryId
     * @return array
     * @throws ClientException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseErrorException
     * @throws \Macopedia\Allegro\Model\Api\ClientResponseException
     */
    public function getParameters($categoryId)
    {
        $response = $this->requestGet('/sale/categories/' . $categoryId . '/parameters');
        return $response['parameters'] ?? [];
    }
}
