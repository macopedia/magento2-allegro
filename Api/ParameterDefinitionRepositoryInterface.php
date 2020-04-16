<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Model\Api\ClientException;

interface ParameterDefinitionRepositoryInterface
{

    /**
     * @param int $categoryId
     * @return \Macopedia\Allegro\Api\Data\ParameterDefinitionInterface[]
     * @throws ClientException
     */
    public function getListByCategoryId(int $categoryId): array;

    /**
     * @param int $categoryId
     * @return \Macopedia\Allegro\Api\Data\ParameterInterface[]
     * @throws ClientException
     */
    public function createParametersByCategoryId(int $categoryId): array;
}
