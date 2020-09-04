<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ThreadTest extends TestCase
{

    use DatabaseMigrations;

    protected $thread;

    public function setUp(): void
    {
        parent::setUp();

        $this->thread = create('App\Thread');
    }
    /** @test */
    function a_thread_can_make_a_string_path()
    {
        $thread = make('App\Thread');
        $this->assertEquals(
            "/threads/{$thread->channel->slug}/{$thread->id}",
            $thread->path()
        );
    }


    /** @test */
    function a_thread_has_a_creator()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /** @test */

    public function a_thread_has_replies()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }


    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    function a_thread_can_be_suscribed_to()
    {
        //Given we have a thread
        $thread = create('App\Thread');

        //And a autheiticated user
        // $this->signIn();

        //When the users suscribes to thread
        $thread->suscribe($userId = 1);

        //Then we sould be able to fetch all threads that the usedr has suscribes to;
        $this->assertEquals(1, $thread->suscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    function a_thread_can_be_unsuscribed_from()
    {
        //Given we have a thread
        $thread = create('App\Thread');

        //and a user who is suscribed to the thread
        $thread->suscribe($userId = 1);

        $thread->unsuscribe($userId);

        $this->assertCount(0, $thread->suscriptions);
    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it()
    {
        //Given we have a thread
        $thread = create('App\Thread');

        //and a user who is suscribed to the thread
        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);

        $thread->suscribe();

        $this->assertTrue($thread->isSubscribedTo);
    }
}
