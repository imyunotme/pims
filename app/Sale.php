<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Carbon;
use Auth;

class Sale extends Model{

	protected $table = 'sales';
	protected $primaryKey = 'id';
	protected $fillable = [ 'date', 'product_id', 'receipt', 'reference', 'cost', 'received', 'issued', 'balance', 'remarks', 'status', 'created_by'];
	public $timestamps = true;

	public function inboundRules(){

		return array(
			'product' => 'required|exists:products,code',
			'receipt' => 'nullable',
			'reference' => 'nullable',
			'remarks' => 'max:150'
		);
	}

	public function outboundRules(){

		return array(
			'product' => 'required|exists:products,code',
			'receipt' => 'nullable',
			'reference' => 'nullable',
			'remarks' => 'max:150'
		);
	}

	protected $appends = [
		'received_total', 'issued_total', 'product_details', 'parsed_date'
	];

	public function getRemarksAttribute($value)
	{
		if($value == null || $value == '')
			return 'N/A';
		else
			return $value;
	}

    public function getIssuedAttribute($value)
    {
        return number_format($value);
    }

    public function getReceivedAttribute($value)
    {
        return number_format($value);
    }

	public function getReceiptAttribute($value)
	{
		if($value == null) return 'N/A';

		return $value;
	}

	public function getReferenceAttribute($value)
	{
		if($value == null) return 'N/A';

		return $value;
	}

	public function getParsedDateAttribute()
	{
		return Carbon\Carbon::parse($this->date)->toFormattedDateString();
	}

	public function getReceivedTotalAttribute($query)
	{
		return 	($this->received * $this->received_amount);
	}

	public function getIssuedTotalAttribute($query)
	{
		return 	($this->issued * $this->issued_amount);
	}

	public function getProductDetailsAttribute()
	{

		return  $this->product->product_details ;
	}

	public function computeBalanceAttribute()
	{
		$sale = Sale::orderBy('date','desc')->orderBy('created_at','desc')->orderBy('id','desc')->first();

		if(count($sale) > 0) $balance = $sale->balance;
		else
			$balance = 0;


		$issued_total = $this->issued_amount * (isset($this->issued) && $this->issued > 0 ? $this->issued : 1);
		$received_total = $this->received_amount * (isset($this->received) && $this->received > 0 ? $this->received : 1);

		$this->balance = $balance + ($issued_total - $received_total);
	}

	public function product()
	{
		return $this->belongsTo('App\Product', 'product_id', 'id');
	}

	public function inbound()
	{
		if(isset($this->issued) && $this->product_id)
		{
			$stockcard = new StockCard;
			$stockcard->date = $this->date;
            $stockcard->product_id = $this->product_id;
            $stockcard->status = $this->status;
            $stockcard->reference = $this->referenc;
            $stockcard->receipt = $this->receipt;
            $stockcard->issued = $this->issued;
            $stockcard->remarks = $this->remarks;
            $stockcard->setBalance();
            $stockcard->created_by = Auth::user()->id;
			$stockcard->issue();	
		}

		$this->computeBalanceAttribute();
		
		$this->created_by = Auth::user()->id;
		$this->save();
	}

	public function outbound()
	{

		if(isset($this->received) && $this->product_id)
		{
			$stockcard = new StockCard;
			$stockcard->date = $this->date;
            $stockcard->product_id = $this->product_id;
            $stockcard->status = $this->status;
            $stockcard->reference = $this->reference;
            $stockcard->receipt = $this->receipt;
            $stockcard->received = $this->received;
            $stockcard->remarks = $this->remarks;
            $stockcard->setBalance();
            $stockcard->created_by = Auth::user()->id;
			$stockcard->receipt();		
		}

		$this->computeBalanceAttribute();
		$this->created_by = Auth::user()->id;
		$this->save();
	}

}
