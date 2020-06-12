<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */

    function guest_may_not_create_threads()
    {
 
        $this->withExceptionHandling();

        $this->get('/threads/create')
             ->assertRedirect('/login');

        $this->post('/threads')
             ->assertRedirect('/login');
        
    }
    
    /** @test */

    function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        //When we hit the endpoint to create a new thread
        //$thread = create('App\Thread');
        //dd($thread->title);
        //$this->post('/threads', $thread->toArray());

        //I need create a user to get the id,or I can use $response->headres to simulate one
        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());

        //Then, when we visit the thread,
        //We should see the new thread
        //$this->get($thread->path())
        $this->get($response->headers->get('location'))
             ->assertSee($thread->title)
             ->assertSee($thread->body);

    }

    /** @test */

    function a_thread_requires_a_title()
    {
        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');
        /*
        $this->withExceptionHandling();
        $this->signIn();

        $thread = make('App\Thread', ['title' => null]);

        $this->post('/threads', $thread->toArray())
            ->assertSessionHasErrors('title');
        */
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_valid_channel()
    {
        factory('App\Channel', 2);

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');
        
        $this->publishThread(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function publishThread($overrides =[])
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }

}
