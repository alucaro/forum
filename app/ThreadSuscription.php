<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadSuscription extends Model
{
    //to solve the allow mass assignment error
    protected $guarded = [];
}
