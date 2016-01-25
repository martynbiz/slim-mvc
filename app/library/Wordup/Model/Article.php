<?php

namespace Wordup\Model;

use MartynBiz\Validator;

/**
 *
 */
class Article extends Base
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
        'content',
        'tags',
        'photos',
    );

    // public function validate()
    // {
    //     $this->resetErrors();
    //
    //     return empty($this->getErrors());
    // }

    /**
     * Find articles which belong to a given $user
     * @param App\Model\User $user
     * @param MartynBiz\Mongo\MongoIterator
     */
    public function findArticlesOf(User $user)
    {
        return $this->find(array(
            'author' => $user,
        ));
    }
}
