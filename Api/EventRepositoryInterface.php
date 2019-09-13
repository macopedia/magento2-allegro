<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\EventInterface;
use Macopedia\Allegro\Model\Api\ClientException;

interface EventRepositoryInterface
{

    /**
     * @return EventInterface[]
     * @throws ClientException
     */
    public function getList(): array;

    /**
     * @param string $from
     * @return EventInterface[]
     * @throws ClientException
     */
    public function getListFrom(string $from): array;
}
