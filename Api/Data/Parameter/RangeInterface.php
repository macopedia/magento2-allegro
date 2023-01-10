<?php

namespace Macopedia\Allegro\Api\Data\Parameter;

use Macopedia\Allegro\Api\Data\ParameterInterface;

interface RangeInterface extends ParameterInterface
{
    /**
     * @param string $minValue
     * @return void
     */
    public function setMinValue(string $minValue);

    /**
     * @param string $maxValue
     * @return void
     */
    public function setMaxValue(string $maxValue);

    /**
     * @return string|null
     */
    public function getMinValue(): ?string;

    /**
     * @return string|null
     */
    public function getMaxValue(): ?string;
}
