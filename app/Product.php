<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps = true;
    protected $fillable = ['code','supply_id','category_id','unit_id','cost'];

    public function rules(){

        return array(
            'code' => 'unique:products,code',
            'supply' => 'nullable|exists:Supplies,id',
            'category' => 'required|exists:Categories,id',
            'unit' => 'nullable|exists:Units,id',
            'cost' => 'nullable'
        );
    }

    public function updateRules(){
        $code = $this->code;
        return array(
            'code' => 'unique:products,code,' . $code . ',code',
            'supply' => 'nullable|exists:Supplies,id',
            'category' => 'required|exists:Categories,id',
            'unit' => 'nullable|exists:Units,id',
            'cost' => ''
        );
    }

    protected $appends = [
        'balance'
    ];

    public function getBalanceAttribute()
    {
        $stockcard = StockCard::where('product_id','=', $this->id)->orderBy('date','desc')->orderBy('created_at','desc')->orderBy('id','desc')->first();

        if(count($stockcard) > 0)
            return $stockcard->balance;
        else
            return 0;
    }

    public function scopeFindByCategoryName($query, $category)
    {

        if($category)
        {
            return $query->whereHas('category', function($query) use($category){
                $query->where('name', '=', $category );
            });
        }
    }

    public function scopeFindBySupplyName($query, $supply)
    {

        if($supply)
        {
            return $query->whereHas('supply', function($query) use($supply){
                $query->where('name', '=', $supply );
            });
        }
    }

    public function scopeFindByUnitName($query, $unit)
    {

        if($unit)
        {
            return $query->whereHas('unit', function($query) use($unit){
                $query->where('name', '=', $unit );
            });
        }
    }
    
    public function supply()
    {
      return $this->belongsTo('App\Supply','supply_id', 'id');
    }    
    
    public function category()
    {
      return $this->belongsTo('App\Category','category_id', 'id');
    }    

    public function unit()
    {
      return $this->belongsTo('App\Unit','unit_id', 'id');
    }

    public function stockcard()
    {
        return $this->hasMany('App\Stockcard');
    }

}
