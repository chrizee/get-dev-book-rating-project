<?php
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => Hash::make("password"),
        'api_token' => str_random(60)
    ];
});

$factory->define(App\Models\Book::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(4),
        'author' => $faker->name,
        'isbn' => $faker->numberBetween(1000, 1000000),
        'description' => $faker->paragraph(),
        'user_id' => $faker->numberBetween(1,10),
    ];
});

$factory->define(App\Models\Rating::class, function (Faker\Generator $faker) {
    return [
        'rating' => $faker->numberBetween(1,5),
        'user_id' => $faker->numberBetween(1,10),
        'book_id' => $faker->numberBetween(1,20),        
    ];
});
