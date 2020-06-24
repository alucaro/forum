<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {
        //\DB::table('favorites')->insert([ //we going to use a facade
        /*
        Favorite::create([
            'user_id'=> auth()->id(),
            'favorited_id' => $reply->id,
            'favorited_type' => get_class($reply)
        ]);
        */

        //we use a polimorphic relationship, this autocomplete the $table
        //just need provide the user_id
        //$reply->favorites()->create(['user_id' => auth()->id()]);
        //now we going to be more specific, need afavorite, and add this
        //function on Reply.php 
        return $reply->favorite();
        // if I want can pass $user_id like param
        //return $reply->favorite(auth()->id());

    }
}
