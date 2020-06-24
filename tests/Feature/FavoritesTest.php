<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()
             ->post('replies/1/favorites')
             ->assertRedirect('/login');
    }

    /** @test */
    
    public function an_authenticated_user_can_favorite_any_reply()
    {
        //$this->withExceptionHandling();

        $this->signIn();

        // /replies/id/favorites
        $reply = create('App\Reply'); // I dont need create a thread because Reply create one

        // if I post to a 'favorite' endpoint
        $this->post('replies/'.$reply->id.'/favorites');

        // it should be recorded in the database
        //dd(\App\Favorite::all());
        $this->assertCount(1, $reply->favorites);
    }
    

    /** @test */
    function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        try{
            $this->post('replies/'.$reply->id.'/favorites');
            $this->post('replies/'.$reply->id.'/favorites'); //this don't work, just need the unique 
                                                             //in the table and this entry dont be duplicate
        }catch (\Exception $e) {
            $this->fail('Did not expect to insert the same record set twice');
        }

        //dd(\App\Favorite::all()->toArray());

        $this->assertCount(1, $reply->favorites);
    }

}