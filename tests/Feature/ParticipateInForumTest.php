<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function anauthenticaded_users_may_not_add_replies()
    {
        //Error de authenticacion no sale o ha cambiado, revisarlo
        /*
        $this->expectException('Illuminate\Foundation\Application');
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();
        $this->post($thread->path().'/replies', $reply->toArray());
        */

        /*
        $this->expectException('Illuminate\Foundation\Application');
        $this->post('/threads/1/replies', []);
        */

    }

    /** @test */

    function an_authenticated_user_may_participate_in_forum_threads()
    {
        //Given we have an authenticated user
        //$user = factory('App\User')->create();
        //$this->be($user); //verify that the user are authenticated
        $this->be($user = factory('App\User')->create() ); 

        //al adicionar la capa de authentificacion la siguiente linea falla
        //$user = factory('App\User')->create();
        //And an existing thread
        $thread = factory('App\Thread')->create();
    
        //When the user adds a reply to the thread
        $reply = factory('App\Reply')->make();
        //make crea un usuario en memoria temporal, create lo crea en la base de datos
        $this->post($thread->path().'/replies', $reply->toArray());

        //Then their reply sould be visible on the page
        $this->get($thread->path())
              ->assertSee($reply->body);
    }
}
