<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
use DB;

class Supply extends Model{

	protected $table = 'supplies';
	protected $fillable = ['name','details'];
	protected $primaryKey = 'id';
	public $timestamps = true;
	public static $rules = array(
		'name' => 'required|unique:supplies,name',
		'details' => ''
	);

	public function updateRules()
	{
		$name = $this->name;
		return array(
				'name' => 'required|unique:supplies,name,' . $name . ',name',
				'details' => ''
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
