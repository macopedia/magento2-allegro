<?php

namespace Macopedia\Allegro\Api\Data;

use Macopedia\Allegro\Api\Data\Parameter\RangeInterface;
use Macopedia\Allegro\Api\Data\Parameter\ValuesIdsInterface;
use Macopedia\Allegro\Api\Data\Parameter\ValuesInterface;

interface ParameterInterfaceFactoryInterface
{
    const TYPES = [
        ParameterDefinitionInterface::FRONTEND_TYPE_RANGE => RangeInterface::class,
        ParameterDefinitionInterface::FRONTEND_TYPE_VALUES => ValuesInterface::class,
        ParameterDefinitionInterface::FRONTEND_TYPE_VALUES_IDS => ValuesIdsInterface::class,
    ];

    /**
     * @param string $type
     * @return RangeInterface|ValuesIdsInterface|ValuesInterface
     */
    public function create(string $type): RangeInterface|ValuesIdsInterface|ValuesInterface;

    /**
     * @param ParameterDefinitionInterface $parameterDefinition
     * @return RangeInterface|ValuesIdsInterface|ValuesInterface
     */
    public function createFromDefinition(ParameterDefinitionInterface $parameterDefinition): RangeInterface|ValuesIdsInterface|ValuesInterface;
}
