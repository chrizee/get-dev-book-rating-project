<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

class BookTest extends TestCase
{
    public function testThatWeCanGetAllBooksWithoutAuthentication()
    {
        $this->get('/api/v1/book')->assertResponseOk();
    }

    public function testThatABookCanBeCreated()
    {
        $user = factory('App\Models\User')->create();

        $this->actingAs($user)
             ->json('POST', '/api/v1/book/store', [
            'name' => "A song of ice and fire",
            'author' => "George R. R. Martin ",
            'isbn' => 14785698,
            'description' => "Game of thrones",
        ])->seeJson([
            'success' => true
        ])->assertResponseOk();

    }

    public function testThatABookCanBeUpdated()
    {
        $user = factory('App\Models\User')->create();
        $book = factory('App\Models\Book')->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
             ->json('PUT', '/api/v1/book/'.$book->id, [
            'name' => "A song of ice and fire",
            'author' => "George R. R. Martin ",
            'isbn' => 14785698,
            'description' => "Game of thrones",
        ])->seeJson([
            'success' => true
        ])->assertResponseOk();

    }

    public function testThatABookCanBeDeleted()
    {
        $user = factory('App\Models\User')->create();
        $book = factory('App\Models\Book')->create([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)
            ->json('DELETE','/api/v1/book/'.$book->id)->seeJson([
                'success' => true
            ])->assertResponseOk();
    }
    
    public function testThatABookCanBeRated()
    {
        $user = factory('App\Models\User')->create();
        $book = factory('App\Models\Book')->create();

        $this->actingAs($user)
             ->json('POST', '/api/v1/book/'.$book->id.'/rate' , [
            'rating' => 2,
        ])->seeJson([
            'success' => true
        ])->assertResponseOk();
    }
}
