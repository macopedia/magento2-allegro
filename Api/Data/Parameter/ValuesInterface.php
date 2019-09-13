<?php

namespace Macopedia\Allegro\Api\Data\Parameter;

use Macopedia\Allegro\Api\Data\ParameterInterface;

interface ValuesInterface extends ParameterInterface
{

    /**
     * @param string[] $value
     * @return void
     */
    public function setValue($value);

    /**
     * @return string[]
     */
    public function getValue();
}
