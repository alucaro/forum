<?php

namespace App\Filters;

use App\User;
//use Illuminate\Http\Request; //send to Filters class

class ThreadFilters extends Filters
{
    protected $filters = ['by'];

    /**
     * Filter the query by a given username
     */
    protected function by($username)
    {
        $user = \App\User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }
}
