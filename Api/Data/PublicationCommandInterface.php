<?php

namespace Macopedia\Allegro\Api\Data;

interface PublicationCommandInterface
{

    const ACTION_ACTIVATE = 'ACTIVATE';
    const ACTION_END = 'END';

    /**
     * @param string $offerId
     * @return void
     */
    public function setOfferId(string $offerId);

    /**
     * @param string $action
     * @return void
     */
    public function setAction(string $action);

    /**
     * @return string
     */
    public function getAction(): ?string;

    /**
     * @return string
     */
    public function getOfferId(): ?string;

    /**
     * @return array
     */
    public function getRawData(): array;
}
