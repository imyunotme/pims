<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UserTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		App\User::truncate();
		App\User::insert([
			array(
				'username' => 'admin',
				'password' => Hash::make('12345678'),
				'access' =>'0',
				'firstname' => 'John Joseph',
				'middlename' => '',
				'lastname' => 'Lim',
				'email' => 'elliotalderson@yahoo.com',
				'status' =>'1',
				'department' => 'Executive', 
				'position' => 'head',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			),

			array(
				'username' => 'cashier',
				'password' => Hash::make('12345678'),
				'access' =>'1',
				'firstname' => 'Erick John',
				'middlename' => '',
				'lastname' => 'Yu',
				'email' => 'tyrionlannister@yahoo.com',
				'status' =>'1',
				'department' => 'Treasury',
				'position' => 'head',
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			),
		]);
	}



}
