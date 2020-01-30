<?php

declare(strict_types = 1);

namespace Macopedia\Allegro\Model\OrderImporter;

/**
 * Class to store status info
 */
class Info
{
    /** @var array */
    private $successIds = [];

    /** @var array */
    private $errorsIds = [];

    /**
     * @return array
     */
    public function getSuccessIds()
    {
        return $this->successIds;
    }

    /**
     * @param array $successIds
     * @return Info
     */
    public function setSuccessIds(array $successIds)
    {
        $this->successIds = $successIds;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrorsIds()
    {
        return $this->errorsIds;
    }

    /**
     * @param array $errorsIds
     * @return Info
     */
    public function setErrorsIds(array $errorsIds)
    {
        $this->errorsIds = $errorsIds;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return sprintf(
            "Updated/created orders:\n%s\nOrders with errors:\n%s",
            implode("\n", $this->successIds),
            implode("\n", $this->errorsIds)
        );
    }
}
