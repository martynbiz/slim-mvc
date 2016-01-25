<?php

namespace Wordup\Model;

/**
 *
 */
class Photo extends Base
{
    // collection this model refers to
    protected static $collection = 'photos';

    // define on the fields that can be saved
    protected static $whitelist = array(
        'file_path',
        'caption',
        'type',
    );

    /**
     * @param int $id The ID which to create the hash from
     */
    public static function getDir($id)
    {
        $bits = 8;

        return sprintf("%02x/%02x/%d", $id % pow(2, $bits), ($id >> $bits) % pow(2, $bits), $id);
    }
}
