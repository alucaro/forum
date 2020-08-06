<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{

    //adicionamos una capa de authentificacion
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function store($channel_id, Thread $thread)
    {
        $this->validate(request(), ['body' => 'required']);

        $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
            ]);

        return back()->with('flash', 'Your reply has been left.');
    }

    public function destroy (Reply $reply)
    {

        // if ($reply->user_id != auth()->id()){
        //     return \response([], 403);
        // }

        //after use policy, we can update this to
        $this->authorize('update', $reply);
        
        $reply->delete();

        return back();
    }

    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);
        
        $reply->update( request(['body']) );
    }
}
