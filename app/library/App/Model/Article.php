<?php

namespace App\Model;

use MartynBiz\Mongo;

/**
 *
 */
class Article extends Mongo
{
    // collection this model refers to
    protected $collection = 'articles';

    // define on the fields that can be saved
    protected $whitelist = array(
        'title',
        'description',
    );
}
