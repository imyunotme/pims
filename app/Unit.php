<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
class Unit extends Model{

	protected $table = 'units';

	public $timestamps = true;

	protected $fillable = ['name','description','abbreviation'];
	protected $primaryKey = 'id';
	
	public static $rules = array(
		'name' => 'required|unique:units,name',
		'description' => '',
		'abbreviation' => 'required|unique:units,abbreviation'
	);

	public function updateRules(){
		$name = $this->name;
		$abbreviation = $this->abbreviation;
		return  array(
			'name' => 'required|unique:units,name,'.$name.',name',
			'nescription' => '',
			'abbreviation' => 'required|unique:units,abbreviation,'.$abbreviation.',abbreviation'
		);
	}

	public function scopeFindByName($query, $value)
	{
		return $query->where('name', '=', $value);
	} 

	public function product()
	{
		return $this->hasMany('App\Product');
	}
}
