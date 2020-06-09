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
        $thread = create('App\Thread');
        //dd($thread->title);
        $this->post('/threads', $thread->toArray());

        //Then, when we visit the thread,
        //We should see the new thread
        $this->get($thread->path())
             ->assertSee($thread->title)
             ->assertSee($thread->body);

    }

}
