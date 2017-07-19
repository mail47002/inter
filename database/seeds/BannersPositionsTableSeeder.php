<?php

use Illuminate\Database\Seeder;

class BannersPositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banners_positions')->insert([
            'name' => 'top',
        ], [
            'name' => 'left',
        ], [
            'name' => 'right',
        ], [
            'name' => 'bottom'
        ]);
    }
}
