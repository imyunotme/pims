<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SupplyTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		App\Supply::truncate();
		App\Supply::insert([
			array( 
			'name' => 'CandyDrum',
			'details' => 'Drum of candy',
	 		),
	 		array( 
			'name' => 'CandyKilo',
			'details' => 'Candy Retail',
	 		),
			array( 
			'name' => 'Bigas_Blue_Sako',
			'details' => 'Rice',
	 		),
			array( 
			'name' => 'Bigas_Green_Sako',
			'details' => 'Rice',
	 		),
			array( 
			'name' => 'Bigas_Violet_Sako',
			'details' => 'Rice',
	 		),
			array( 
			'name' => 'Bigas_Yellow_Sako',
			'details' => 'Rice',
	 		),
			array( 
			'name' => 'Bigas_Yellow_Peacock',
			'details' => 'Rice',
	 		),
			array( 
			'name' => 'Bigas_TigerRed_Sako',
			'details' => 'Rice',
	 		),
			array( 
			'name' => 'Gamot_Panlalaki',
			'details' => 'Happy King',
	 		),
			array( 
			'name' => 'Gamot_FastAct',
			'details' => 'pangtanggal sakit ng muscles and joints',
	 		),
		]);
	}
}

