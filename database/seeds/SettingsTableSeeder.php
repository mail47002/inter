<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key' => 'credits_per_registration',
                'value'     => 100
            ], [
                'key'       => 'credits_rate',
                'value'     => 1
            ], [
                'key'       => 'featured_rate',
                'value'     => 1
            ]
        ]);
    }
}
