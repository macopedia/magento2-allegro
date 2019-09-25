<?php

namespace Macopedia\Allegro\Api\Data\Parameter;

use Macopedia\Allegro\Api\Data\ParameterInterface;

interface ValuesIdsInterface extends ParameterInterface
{

    /**
     * @param int[] $value
     * @return void
     */
    public function setValue(array $value);

    /**
     * @return int[]
     */
    public function getValue(): array;
}
