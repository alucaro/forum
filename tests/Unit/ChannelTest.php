<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ChannelTest extends TestCase
{

    use DatabaseMigrations;

    /**Test */
    /*
    public function a_channel_consist_of_threads()
    {
        $channel = create('App\Channel');
        $thread = create('App\Thread', ['channel_id' => $channel->id]);

        dd($this);
        //$this->assertTrue($channel->threads->contains($thread));
    }
    */

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTestdos()
    {

        $channel = create('App\Channel');
        $thread = create('App\Thread', ['channel_id' => $channel->id]);

        $this->assertTrue($channel->threads->contains($thread));
    }



}
