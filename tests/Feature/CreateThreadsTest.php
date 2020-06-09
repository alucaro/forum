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
        $this->withoutExceptionHandling();//para obtener mas informacion acerca del error
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = make('App\Thread');
        $this->post('/threads' , $thread->toArray());
    }
    /** @test */

    function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        //When we hit the endpoint to create a new thread
        $thread = make('App\Thread');
        //dd($thread->title);
        $this->post('/threads', $thread->toArray());

        //Then, when we visit the thread,
        //We should see the new thread
        $this->get($thread->path())
             ->assertSee($thread->title)
             ->assertSee($thread->body);

    }

    /** @test */

    function guests_cannot_see_the_create_thread_page()
    {
        //$this->withoutExceptionHandling();

        $this->withExceptionHandling()
             ->get('/threads/create')
             ->assertRedirect('/login');
    }
}
