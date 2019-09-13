<?php

namespace Macopedia\Allegro\Model\Api;

class ClientResponseErrorException extends ClientResponseException
{
    /** @var string[] */
    private $errors = [];

    /**
     * @param string $error
     */
    public function addError(string $error)
    {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
