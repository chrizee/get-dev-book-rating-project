<?php

use Illuminate\Database\Seeder;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Book;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        DB::table('books')->truncate();
        DB::table('ratings')->truncate();

        factory(App\Models\User::class, 10)->create();
        factory(Book::class, 20)->create();
        factory(Rating::class, 20)->create();
        
        Schema::enableForeignKeyConstraints();
    }
}
