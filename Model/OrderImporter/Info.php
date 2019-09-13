<?php

namespace Macopedia\Allegro\Model\OrderImporter;

/**
 * Class to store status info
 */
class Info
{
    /** @var int */
    private $importedCount;

    /** @var int */
    private $updatedCount;

    /** @var int */
    private $errorsCount;

    /**
     * @return int
     */
    public function getImportedCount()
    {
        return $this->importedCount;
    }

    /**
     * @param int $importedCount
     * @return Info
     */
    public function setImportedCount($importedCount)
    {
        $this->importedCount = $importedCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }

    /**
     * @param int $updatedCount
     * @return Info
     */
    public function setUpdatedCount($updatedCount)
    {
        $this->updatedCount = $updatedCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getErrorsCount()
    {
        return $this->errorsCount;
    }

    /**
     * @param int $errorsCount
     * @return Info
     */
    public function setErrorsCount($errorsCount)
    {
        $this->errorsCount = $errorsCount;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return sprintf(
            '%s orders has been created, %s orders has been updated, %s errors has been occurred',
            $this->importedCount,
            $this->updatedCount,
            $this->errorsCount
        );
    }
}
