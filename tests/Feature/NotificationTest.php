<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $this->signIn();
    }

    /** @test */
    function a_notification_is_prepared_when_a_suscribed_thread_recives_a_new_reply()
    {


        $thread = create('App\Thread')->suscribe();

        $this->assertCount(0, auth()->user()->notifications);

        $thread->addReply([
            'user_id' => auth()->id(),
            'body' => 'Some reply here'
        ]);

        $this->assertCount(0, auth()->user()->fresh()->notifications);

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some reply here'
        ]);

        $this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test */
    function a_user_can_fetch_their_unread_notifications()
    {
        $this->withoutExceptionHandling();

        create(DatabaseNotification::class);

        // $thread = create('App\Thread')->suscribe();

        // $thread->addReply([
        //     'user_id' => create('App\User')->id,
        //     'body' => 'Some reply here'
        // ]);

        $this->assertCount(1, $this->getJson('/profiles/' . auth()->user()->name . '/notifications/')->json());
    }

    /** @test */

    function a_user_can_mark_a_notification_as_read()
    {
        $this->withoutExceptionHandling();

        create(DatabaseNotification::class);
        // $thread = create('App\Thread')->suscribe();

        // $thread->addReply([
        //     'user_id' => create('App\User')->id,
        //     'body' => 'Some reply here'
        // ]);


        //tap function value in position one will be pass to the position two inmidiatly
        tap(auth()->user(), function ($user) {
            $this->assertCount(1, $user->unreadNotifications);

            $notificationId = $user->unreadNotifications->first()->id;

            $this->delete('/profiles/' . $user->name . '/notifications/' . $user->unreadNotifications->first()->id);

            $this->assertCount(0, $user->fresh()->unreadNotifications);
        });
    }
}
