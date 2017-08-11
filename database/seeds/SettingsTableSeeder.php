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
                'key' => 'registration_credits',
                'value'     => 100
            ], [
                'key'       => 'campaigns_credits',
                'value'     => 10
            ], [
                'key'       => 'featured_credits',
                'value'     => 5
            ]
        ]);
    }
}
