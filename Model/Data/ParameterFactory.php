<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\Parameter\RangeInterface;
use Macopedia\Allegro\Api\Data\Parameter\ValuesIdsInterface;
use Macopedia\Allegro\Api\Data\Parameter\ValuesInterface;
use Macopedia\Allegro\Api\Data\ParameterDefinitionInterface;
use Macopedia\Allegro\Api\Data\ParameterInterface;
use Macopedia\Allegro\Api\Data\ParameterInterfaceFactoryInterface;
use Magento\Framework\ObjectManagerInterface;

class ParameterFactory implements ParameterInterfaceFactoryInterface
{

    /** @var ObjectManagerInterface */
    private $objectManager;

    /**
     * ParameterFactory constructor.
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $type
     * @return RangeInterface|ValuesIdsInterface|ValuesInterface
     * @throws ParameterFactoryException
     */
    public function create(string $type): ParameterInterface
    {
        if (!isset(self::TYPES[$type])) {
            throw new ParameterFactoryException(__('Requested parameter type does not exist'));
        }

        return $this->objectManager->create(self::TYPES[$type]);
    }

    /**
     * @param ParameterDefinitionInterface $parameterDefinition
     * @return RangeInterface|ValuesIdsInterface|ValuesInterface
     * @throws ParameterFactoryException
     */
    public function createFromDefinition(ParameterDefinitionInterface $parameterDefinition): ParameterInterface
    {
        $parameter = $this->create($parameterDefinition->getFrontendType());
        $parameter->setId($parameterDefinition->getId());
        return $parameter;
    }
}
