<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{

    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    //init authomatic with the program
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function ($builder) {
            $builder->withCount('replies');
        });

        static::deleting(function ($thread) {
            $thread->replies()->delete();
        });

        // static::created(function ($thread) {
        //     // Activity::create([
        //     //     'user_id' => auth()->id(),
        //     //     'type' => 'created_' . strtolower ((new \ReflectionClass($thread))->getShortName()), //Give us Thread instead of Forum\App\Thread
        //     //     'subject_id' => $thread->id,
        //     //     'subject_type' => get_class($thread)
        //     // ]);
        //     $thread->recordActivity('created');
            
        // });

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
        $this->replies()->create($reply);
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

}
