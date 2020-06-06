<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    public function path()
    {
        //any place in document you call to $thead->path(), return this path
        return '/threads/'.$this->id;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

}
