<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\ParameterDefinitionInterface;
use Macopedia\Allegro\Api\Data\ParameterInterface;
use Macopedia\Allegro\Model\Api\ClientException;

interface ParameterDefinitionRepositoryInterface
{

    /**
     * @param int $categoryId
     * @return ParameterDefinitionInterface[]
     * @throws ClientException
     */
    public function getListByCategoryId(int $categoryId): array;

    /**
     * @param int $categoryId
     * @return ParameterInterface[]
     * @throws ClientException
     */
    public function createParametersByCategoryId(int $categoryId): array;
}
