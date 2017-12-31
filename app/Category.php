<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
class Category extends Model{

	protected $table = 'categories';
	protected $primaryKey = 'id';
	protected $fillable = [ 'code', 'name' ];
	public $timestamps = true;

	public function rules(){
		return array(
			'name' => 'required|max:200|unique:Categories,name'
		);
	}

	public function updateRules(){
		$name = $this->name;
		return array(
			'name' => 'required|max:200|unique:Categories,name,'.$name.',name'
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
