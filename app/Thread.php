<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

class Thread extends Model
{

    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];

    /**
     * Boot the model.
     */

    //init authomatic with the program
    protected static function boot()
    {
        parent::boot();

        //if added a count column in database, dont need this anymore
        // static::addGlobalScope('replyCount', function ($builder) {
        //     $builder->withCount('replies');
        // });

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
            // $thread->replies->each(function ($reply) {
            //     $reply->delete();
            // });
        });
    }



    public function path()
    {
        //any place in document you call to $thead->path(), return this path
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)
            ->withCount('favorites')
            ->with('owner');
    }

    //if I decide that replies count is something that i need everywhere
    //put this in a global scope line 12 Thread.php
    /*
    public function getReplyCountAttribute()
    {
        return $this->replies()->count();
    }
    */

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        //return $this->replies()->create($reply);
        $reply = $this->replies()->create($reply);

        //prepare notifications for all suscribers

        // foreach ($this->suscriptions as $suscription) {

        //     if ($suscription->user_id != $reply->user_id) {
        //         $suscription->user->notify(new ThreadWasUpdated($this, $reply));
        //     }
        // }

        //refactoring

        $this->suscriptions->filter(function ($sub) use ($reply) {

            return $sub->user_id != $reply->user_id;
        })
            ->each->notify($reply);
        // ->each(function ($sub) use ($reply) {
        //     $sub->notify($reply);
        // });

        return $reply;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function suscribe($userId = null)
    {
        $this->suscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsuscribe($userId = null)
    {
        $this->suscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }

    public function suscriptions()
    {
        return $this->hasMany(ThreadSuscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->suscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
