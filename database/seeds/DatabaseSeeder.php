<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('aircrafts')->truncate();
        DB::table('airports')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        DB::statement(file_get_contents(database_path('seeds/airports.sql')));
        DB::statement(file_get_contents(database_path('seeds/aircraft.sql')));
    }
}
