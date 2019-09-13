<?php

namespace Macopedia\Allegro\Model;

/**
 * Class to get GUID
 */
class Guid
{
    /**
     * @param bool $trim
     * @return string
     */
    public function getGuid($trim = true)
    {
        $charId = strtolower(
            hash(
                'sha256',
                uniqid(
                    rand(),
                    true
                )
            )
        );
        $hyphen = '-';
        $guidv4 = ($trim ? '' : '{') .
            substr($charId, 0, 8) . $hyphen .
            substr($charId, 8, 4) . $hyphen .
            substr($charId, 12, 4) . $hyphen .
            substr($charId, 16, 4) . $hyphen .
            substr($charId, 20, 12) .
            ($trim ? '' : '}');
        return $guidv4;
    }
}
