<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class SuscribeToThreadTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_suscribe_to_thread()
    {
        // $this->withoutExceptionHandling();

        $this->signIn();

        //Given we have a Thread
        $thread = create('App\Thread');

        //and the user suscribe to the thread
        $this->post($thread->path() . '/suscriptions');

        //Then, each time a new reply is left
        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);

        //A notification should be prepared for the user
        // $this->assertCount(1, auth()->user()->notifications);
    }

    /** @test */
    public function a_user_can_unsuscribe_from_thread()
    {
        // $this->withoutExceptionHandling();

        $this->signIn();

        //Given we have a Thread
        $thread = create('App\Thread');

        $thread->suscribe();

        //and the user suscribe to the thread
        $this->delete($thread->path() . '/suscriptions');

        $this->assertCount(0, $thread->suscriptions);
    }
}
