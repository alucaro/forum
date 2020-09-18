<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Notifications\ThreadWasUpdated;

class ThreadSuscription extends Model
{
    //to solve the allow mass assignment error
    protected $guarded = [];

    //add the relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function notify($reply)
    {
        $this->user->notify(new ThreadWasUpdated($this->thread, $reply));
    }
}
