<?php

namespace Wordup\Model;

use MartynBiz\Validator;
use Wordup\Utils;

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
        'published_at',
    );

    /**
     * Find articles which belong to a given $user
     * @param App\Model\User $user
     * @param array $query Optional query to find articles
     */
    public function findArticlesManagedBy(User $user, $query=array())
    {
        // members can only view their own articles
        if ($user->isMember()) {
            $query = array_merge(array(
                'author' => $user,
            ), $query);
        }

        // TODO editors can only view their members articles

        return $this->find($query);
    }

    /**
     * Convert published_at date to human readable TODO test
     */
    public function getPublishedAt($value)
    {
        return date('d/m/Y h:i', $value->sec);
    }

    /**
     * Additional Save procedures
     */
    public function save($data=array())
    {
        if (empty($this->data['slug'])) {
            $this->data['slug'] = Utils::slugify($this->data['title']);
        }

        return parent::save($data);
    }
}
