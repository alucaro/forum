<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Auth;
use App\Activity;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Carbon\Carbon;

class ActivityTest extends TestCase
{

    use DatabaseMigrations;

    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities', [
            'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    /** @test */
    function it_records_activity_when_a_reply_is_created()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $reply = create('App\Reply');

        $this->assertEquals(2, Activity::count());
    }

    /** @test */
    function it_fetches_a_feed_for_any_user()
    {
        //Given we have a thread
        $this->withExceptionHandling();

        $this->signIn();

        create('App\Thread', ['user_id' => auth()->id()], 2);

        auth()->user()->activity()->first()->update(['created_at' => Carbon::now()->subWeek()]);
        

        // //And another thread for a week ago
        // create('App\Thread', [
        //     'user_id' => auth()->id(),
        //     'created_at' => Carbon::now()->subWeek()
        // ]);

        //When we fetch their feed
        $feed = Activity::feed(auth()->user());

        //Then, it should be returned in proper format
        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
        ));

        $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
        ));
    }
}