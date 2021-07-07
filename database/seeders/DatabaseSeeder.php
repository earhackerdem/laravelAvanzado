<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use FFI;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       User::factory(10)->create();
       $this->call(ProductSeeder::class);
       $this->call(CategorySeeder::class);
    }
}
