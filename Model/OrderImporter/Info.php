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

    /** @var array */
    private $skippedIds = [];

    /**
     * @param array $successIds
     * @return Info
     */
    public function setSuccessIds(array $successIds): Info
    {
        $this->successIds = $successIds;
        return $this;
    }

    /**
     * @param array $errorsIds
     * @return Info
     */
    public function setErrorsIds(array $errorsIds): Info
    {
        $this->errorsIds = $errorsIds;
        return $this;
    }

    /**
     * @param array $skippedIds
     * @return Info
     */
    public function setSkippedIds(array $skippedIds): Info
    {
        $this->skippedIds = $skippedIds;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return sprintf(
            "Created orders:\n%s\nOrders with errors:\n%s\nSkipped orders:\n%s",
            implode("\n", $this->successIds),
            implode("\n", $this->errorsIds),
            implode("\n", $this->skippedIds)
        );
    }
}
