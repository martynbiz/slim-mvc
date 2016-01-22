<?php

namespace App\Model;

use MartynBiz\Mongo;

/**
 *
 */
class Media extends Mongo
{
    /**
     *
     */
    public function getDir()
    {
        if (empty($this->data['id'])) return null;

        $bits = 8;
        $id = $this->data['id'];

        return sprintf("%02x/%02x/%d", $id % pow(2, $bits), ($id >> $bits) % pow(2, $bits), $id);
    }
}
