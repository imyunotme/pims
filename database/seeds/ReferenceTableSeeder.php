<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ReferenceTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
            App\Reference::truncate();
            App\Reference::insert([
                  array(
                        'lastname' => 'guest', 
                        'type'=> 'customer',
                        'company' => 'guest', 
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                  ),
                  
                  array(
                        'lastname' => 'guest', 
                        'type'=> 'supplier',
                        'company' => 'guest', 
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                  ),
            ]);
      }
}
