<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\ReturnPolicyInterface;

interface ReturnPolicyRepositoryInterface
{

    /**
     * @return ReturnPolicyInterface[]
     */
    public function getList(): array;
}
