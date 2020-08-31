<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /*

    function unauthenticaded_users_may_not_add_replies()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->make();
        $this->post($thread->path().'/replies', $reply->toArray());

    } 
    */

    /** @test */

    function unauthenticated_users_may_not_add_replies()
    {
        /*
        $this->withoutExceptionHandling();
        $this->expectException('Illuminate\Auth\AuthenticationException');
        $response = $this->post('/threads/some-channel/1/replies', []);
        $response->assertRedirect(route('login'));
        */
        $this->withExceptionHandling()
            ->post('/threads/some-channel/1/replies', [])
            ->assertRedirect(route('login'));
    }




    /** @test */

    function an_authenticated_user_may_participate_in_forum_threads()
    {
        //$user = factory('App\User')->create();
        $this->be($user = create('App\User'));

        $thread = create('App\Thread');

        $reply = make('App\Reply');

        $this->post($thread->path() . '/replies', $reply->toArray());

        // $this->get($thread->path())
        //       ->assertSee($reply->body);
        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }

    /** @test */

    function a_reply_requires_a_body()
    {
        $this->withExceptionHandling()->signIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path() . '/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */

    function unauthorized_users_cannot_delete_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */

    function authorized_users_can_delete_replies()
    {
        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $this->delete("/replies/{$reply->id}")->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }

    /** @test */

    function unauthorized_users_cannot_update_replies()
    {
        $this->withExceptionHandling();

        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()
            ->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }

    /** @test */

    function authorized_users_can_update_replies()
    {
        // $this->withExceptionHandling();

        $this->signIn();

        $reply = create('App\Reply', ['user_id' => auth()->id()]);

        $updatedReply = 'You been changed, fool.';

        $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
    }
}
