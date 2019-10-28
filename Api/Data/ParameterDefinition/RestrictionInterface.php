<?php

namespace Macopedia\Allegro\Api\Data\ParameterDefinition;

interface RestrictionInterface
{

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type);

    /**
     * @param mixed $value
     * @return void
     */
    public function setValue($value);

    /**
     * @return string
     */
    public function getType(): ?string;

    /**
     * @return mixed
     */
    public function getValue();
}
