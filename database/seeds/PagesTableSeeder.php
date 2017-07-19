<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pages')->insert([
            'title'     => str_random(10),
            'slug'      => str_slug(str_random(10)),
            'content'   => str_random(255),
            'status'    => 1,
        ]);
    }
}
