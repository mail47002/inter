<?php

use Illuminate\Database\Seeder;
use App\Page;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call('PagesSeeder');
    }
}

class PagesSeeder extends Seeder
{

	public function run()
	{
		DB::table('pages')->delete();
        Page::create([
            'title' => 'First Post',
            'slug' => 'first-post',
            'content' => 'Content First Post body',
            'published' => 1,
        ]);

        Page::create([
            'title' => 'Second Post',
            'slug' => 'second-post',
            'content' => 'Content Second Post body',
            'published' => 1,
        ]);
	}

}
