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

    public function a_thread_has_replies()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    function a_thread_has_a_creator()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\User', $this->thread->creator);
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

}
