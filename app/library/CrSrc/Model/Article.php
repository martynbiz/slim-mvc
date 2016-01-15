<?php

namespace CrSrc\Model;

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

    public function validate()
    {
        $this->resetErrors();

        if (empty($this->data['title'])) {
            $this->setError('Title must be given');
        }

        if (empty($this->data['description'])) {
            $this->setError('Description must be given');
        }

        return empty($this->getErrors());
    }
}
