<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => "api/v1"], function () use ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('register', "UserController@register");
        $router->post('login', "UserController@login");
    });
    
    $router->get("book", "BooksController@index");
    
    $router->group(['middleware' => 'auth', 'prefix' => 'book'], function () use ($router) {
        $router->post('store', "BooksController@store");
        
        $router->post('/{book}/rate', "BooksController@rate");
    
        $router->put('/{book}', "BooksController@update");
    
        $router->delete("/{book}", "BooksController@delete");
    });
    
});
