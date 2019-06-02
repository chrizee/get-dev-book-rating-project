<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    /**
     * Test that a user can register with correct details
     *
     * @return void
     */
    public function testThatAUserWithCorrectCredentialsCanRegister()
    {
        $password = Hash::make("password");
        $this->json('POST', '/api/v1/auth/register', [
            'name' => "John Doe",
            'email' => "john@example.com",
            'password' => $password,
            'password_confirmation' => $password
        ])->seeJson([
                'success' => true,
             ]);
    }

    /**
     * Test that a user cannot register with incorrect details
     *
     * @return void
     */
    public function testThatAUserWithIncorrectCredentialsCannotRegister()
    {
        $password = Hash::make("password");
        $response = $this->call('POST', '/api/v1/auth/register', [
            'name' => "John Doe",
            'email' => "john@example.com",
            'password' => $password,
            //'password_confirmation' => $password
        ]);
        $this->assertEquals(400, $response->status());
    }

    /**
     * Test that a user can login
     *
     * @return void
     */
    // public function testThatAUserWithCorrectCredentialsCanLogin()
    // {
    //     $password = Hash::make("password");        
    //     $this->json('POST', '/api/v1/auth/login', [           
    //         'email' => "john@example.com",
    //         'password' => $password,
    //     ])->seeJson([
    //             'success' => true,
    //          ]);
    // }
}
