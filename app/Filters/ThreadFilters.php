<?php

namespace App\Filters;

use App\User;
//use Illuminate\Http\Request; //send to Filters class

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by a given username
     * 
     * @param string $usernameQreturn Builder
     */
    protected function by($username)
    {
        $user = \App\User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Filter the query according to most popular threads
     * @return $this
     */
    protected function popular()
    {
        //we define a default order (date) in sql request, for remove and pass the order that I want
        //we can do this:
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    protected function unanswered()
    {

        return $this->builder->where('replies_count', 0);
    }
}
