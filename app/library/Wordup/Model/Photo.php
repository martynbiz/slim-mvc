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
        'original_file',
        'type',
        'width',
        'height',
    );

    /**
     * Will generate a new directory based on the date now
     * @return string
     */
    public static function getNewDir()
    {
        return date('Ym/d');
    }

    /**
     * Will return directory of this photo
     * @return string
     */
    public function getDir()
    {
        return date('Ym/d', $this->data['created_at']->sec);
    }

    /**
     * Will get the path to the cached photo by given dimensions
     * @param string $dim Dimensions to get (e.g. "100x100")
     */
    public function getCachedPath($dim)
    {
        $datePart = date('Ym/d', $this->data['created_at']->sec);

        return '/' . $datePart . '/' . $this->data['id'] . '-' . $dim . '.jpg';
    }

    /**
     * Will get the path to the original photo (not cached)
     * @param int $id The ID which to create the hash from
     */
    public function getOriginalPath($dim)
    {
        $datePart = date('Ym/d', $this->data['created_at']->sec);

        return '/' . $datePart . '/' . $this->data['original_file'];
    }
}
