<?php

namespace Macopedia\Allegro\Model;

use Macopedia\Allegro\Api\Data\ParameterDefinitionInterface;
use Macopedia\Allegro\Api\Data\ParameterDefinitionInterfaceFactory;
use Macopedia\Allegro\Api\Data\ParameterInterfaceFactoryInterface;
use Macopedia\Allegro\Api\ParameterDefinitionRepositoryInterface;
use Macopedia\Allegro\Model\Api\ClientResponseException;
use Macopedia\Allegro\Model\ResourceModel\Sale\Categories;

class ParameterDefinitionRepository implements ParameterDefinitionRepositoryInterface
{

    /** @var Categories */
    private $categories;

    /** @var ParameterDefinitionInterfaceFactory */
    private $parameterDefinitionFactory;

    /** @var ParameterInterfaceFactoryInterface */
    private $parameterFactory;

    /**
     * ParameterDefinitionRepository constructor.
     * @param Categories $categories
     * @param ParameterDefinitionInterfaceFactory $parameterDefinitionFactory
     * @param ParameterInterfaceFactoryInterface $parameterFactory
     */
    public function __construct(
        Categories $categories,
        ParameterDefinitionInterfaceFactory $parameterDefinitionFactory,
        ParameterInterfaceFactoryInterface $parameterFactory
    ) {
        $this->categories = $categories;
        $this->parameterDefinitionFactory = $parameterDefinitionFactory;
        $this->parameterFactory = $parameterFactory;
    }

    /**
     * {@inheritDoc}
     */
    public function getListByCategoryId(int $categoryId): array
    {
        try {

            $parametersData = $this->categories->getParameters($categoryId);

        } catch (ClientResponseException $e) {
            return [];
        }

        $parameters = [];
        foreach ($parametersData as $parameterDefinitionData) {
            /** @var ParameterDefinitionInterface $parameter */
            $parameter = $this->parameterDefinitionFactory->create();
            $parameter->setRawData($parameterDefinitionData);
            $parameters[] = $parameter;
        }
        return $parameters;
    }

    /**
     * {@inheritDoc}
     */
    public function createParametersByCategoryId(int $categoryId): array
    {
        $parameters = [];
        foreach ($this->getListByCategoryId($categoryId) as $parameterDefinition) {
            $parameters[] = $this->parameterFactory->createFromDefinition($parameterDefinition);
        }
        return $parameters;
    }
}
