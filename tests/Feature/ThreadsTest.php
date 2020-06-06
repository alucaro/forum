<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class ThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function a_user_can_view_all_threads()
    {

        $thread =  factory('App\Thread')->create();
        $response = $this->get('/threads');

        //Puede visitar la pagina /threads
        //$response->assertStatus(200);

        //verifica si la vista /threads contiene un titulo de $threads
        $response->assertSee($thread->title);
    }

    /** @test */
    public function a_user_can_read_a_single_thread()
    {
        $thread =  factory('App\Thread')->create();

        $response = $this->get('threads/'. $thread->id);
        $response->assertSee($thread->title);

    }
}
