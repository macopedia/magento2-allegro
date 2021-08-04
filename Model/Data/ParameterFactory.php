<?php

namespace Macopedia\Allegro\Model\Data;

use Macopedia\Allegro\Api\Data\Parameter\RangeInterface;
use Macopedia\Allegro\Api\Data\Parameter\ValuesIdsInterface;
use Macopedia\Allegro\Api\Data\Parameter\ValuesInterface;
use Macopedia\Allegro\Api\Data\ParameterDefinitionInterface;
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
     * {@inheritDoc}
     */
    public function create(string $type): RangeInterface|ValuesIdsInterface|ValuesInterface
    {
        if (!isset(self::TYPES[$type])) {
            throw new ParameterFactoryException(__('Requested parameter type does not exist'));
        }

        return $this->objectManager->create(self::TYPES[$type]);
    }

    /**
     * {@inheritDoc}
     */
    public function createFromDefinition(ParameterDefinitionInterface $parameterDefinition): RangeInterface|ValuesIdsInterface|ValuesInterface
    {
        $parameter = $this->create($parameterDefinition->getFrontendType());
        $parameter->setId($parameterDefinition->getId());
        return $parameter;
    }
}
