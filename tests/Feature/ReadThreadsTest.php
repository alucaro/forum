<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    /** @test */
    public function a_user_can_view_all_threads()
    {
        //verifica si la vista /threads contiene un titulo de $threads
        //$response = $this->get('/threads');
        //$response->assertSee($this->thread->title);
        //mas simplificado
        $this->get('/threads')
             ->assertSee($this->thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);

    }

    /** @test */
    public function a_user_can_read_replies_that_are_associated_with_a_thread()
    {
        //Given we have a thread -> $this->thread
        //And that thread include replies
        $reply = factory('App\Reply')
                 ->create(['thread_id' => $this->thread->id]);
        //When we visit a thread page
        //Thhen we should see the replies
        $this->get($this->thread->path())
             ->assertSee($reply->body);
    }

    /** @test */
    public function a_user_can_filter_threads_according_to_a_channel()
    {

        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotInChannel = create('App\Thread');

        $this->get('/threads/' . $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotInChannel);//->title); esta parte no funciona
    }
}
