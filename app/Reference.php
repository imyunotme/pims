<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Carbon;

class Reference extends Model{

	protected $table = 'references';
	protected $primaryKey = 'id';
	protected $fillable = [ 'firstname', 'middlename', 'lastname', 'organization_name', 'address', 'contact', 'email', 'description'];
	public $timestamps = false;

	public function rules(){

		$rules = Reference::$types;

		return array(
			'firstname' => 'required|max:100',
			'middlename' => 'max:50',
			'lastname' => 'required|max:50',
			'type' => [
				'required',
				Rule::in($rules),
			],
			'company' => 'max:100',
			'address' => 'max:191',
			'contact' => 'max:191',
			'email' => 'max:100',
			'description' => 'max:150'
		);
	}

	public function updateRules(){

		$rules = Reference::$types;

		return array(
			'firstname' => 'required|max:100',
			'middlename' => 'max:50',
			'lastname' => 'required|max:50',
			'type' => [
				'required',
				Rule::in($rules),
			],
			'company' => 'max:100',
			'address' => 'max:191',
			'contact' => 'max:191',
			'email' => 'max:100',
			'description' => 'max:150'
		);
	}

	public static $types = [
		'customer', 'supplier'
	]; 

	protected $appends = [
		'name'
	];

	public function getNameAttribute()
	{
		return $this->firstname . ' ' . $this->middlename . ' ' . $this->lastname;
	}

	public function scopeFindByCompany($query,$value)
	{
		return $query->where('company','=',$value);
	}

	public function scopeSupplier($query)
	{
		return $query->where('type', '=', 'supplier');
	}

	public function scopeCustomer($query)
	{
		return $query->where('type', '=', 'customer');
	}

}
