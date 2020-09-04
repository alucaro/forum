<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        return $this->replies()->create($reply);
        //This form works, but we going to use model
        // $reply = $this->replies()->create($reply);
        // $this->increment('replies_count');
        // return $reply;
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
