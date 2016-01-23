<?php

namespace App\Model;

use MartynBiz\Mongo;
use MartynBiz\Validator;

/**
 *
 */
class Article extends Mongo
{
    // types
    const TYPE_ARTICLE = 'article';
    const TYPE_PLACE = 'place';

    // statuses
    const STATUS_DRAFT = 0;
    const STATUS_SUBMITTED = 1;
    const STATUS_APPROVED = 2;

    // collection this model refers to
    protected static $collection = 'articles';

    // define on the fields that can be saved
    protected static $whitelist = array(
        'title',
        'slug',
        'description',
    );

    public function validate()
    {
        $this->resetErrors();

        // if (empty($this->data['title'])) {
        //     $this->setError('Title must be given');
        // }
        //
        // if (empty($this->data['description'])) {
        //     $this->setError('Description must be given');
        // }

        return empty($this->getErrors());
    }
}
