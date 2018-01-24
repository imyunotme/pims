<?php

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Category::truncate();
		App\Category::insert([
			array( 
			'name' => 'Bigas',
			'created_by' => 'System'
	 		),
	 		array( 
			'name' => 'Misc',
			'created_by' => 'System'
	 		),
	 		array( 
			'name' => 'Others',
			'created_by' => 'System'
	 		),
	 		array( 
			'name' => 'Gamot',
			'created_by' => 'System'
	 		),
	 		array( 
			'name' => 'Copra',
			'created_by' => 'System'
	 		),
	 		array( 
			'name' => 'Vale',
			'created_by' => 'System'
	 		),
	 		array( 
			'name' => 'Cash In',
			'created_by' => 'System'
	 		),
	 		array( 
			'name' => 'Sulong',
			'created_by' => 'System'
	 		),
	 	]);
    }
}
