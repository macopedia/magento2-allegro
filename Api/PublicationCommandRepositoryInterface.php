<?php

namespace Macopedia\Allegro\Api;

use Macopedia\Allegro\Api\Data\PublicationCommandInterface;
use Macopedia\Allegro\Model\Api\ClientException;
use Magento\Framework\Exception\CouldNotSaveException;

interface PublicationCommandRepositoryInterface
{

    /**
     * @param PublicationCommandInterface $publication
     * @throws ClientException
     * @throws CouldNotSaveException
     */
    public function save(PublicationCommandInterface $publication);
}
