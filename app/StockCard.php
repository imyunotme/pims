<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;
use Auth;
use DB; 
class StockCard extends Model{

	protected $table = 'stockcards';
	protected $primaryKey = 'id';
	public $timestamps = true;
	protected $fillable = [ 'date','product','receipt','reference','quantity','remarks','status'];

	// set of rules when receiving an item
	public static $inRules = array(
		'date' => 'required',
		'product' => 'required|exist:Products,id',
		'receipt' => 'nullable',
		'reference' => '',
		'quantity' => 'required|integer',
		'remarks' => 'nullable',
		'status' => 'nullable'
	);

	//set of rules when issuing the item
	public static $outRules = array(
		'date' => 'required',
		'product ' => 'required|exist:Products,id',
		'receipt' => 'nullable',
		'reference' => '',
		'quantity' => 'required|integer',
		'remarks' => 'nullable',
		'status' => 'nullable'
	);

	protected $appends = [
		'received_total', 'issued_total', 'product_details', 'parsed_date'
	];

	public function getRemarkAttribute($value)
	{
		if($this->remarks == null || $this->remarks == '')
			return 'N\A';
		else
			return $this->remarks;
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
		if(count($this->product))
		{
			$category = count($this->product->category) > 0 ? $this->product->category->name . " - " : "";
			$supply = count($this->product->supply) > 0 ? $this->product->supply->name . " - " : "";
			$unit = count($this->product->unit) > 0 ? $this->product->unit->name . " - " : "";
			return  $category . $supply . $unit ;
		}
	}

	/*
	*	Formats the day to either Month XX XXXX format (a)
	*	or Month XX XXXX format using carbon
	*	a. Carbon\Carbon::parse($value)->format('F d Y');
	*	b. Carbon\Carbon::parse($value)->toFormattedDateString();
	*/
	public function getDateAttribute($value)
	{
		return Carbon\Carbon::parse($value)->toFormattedDateString();
	}

	public function scopeFilterByMonth($query, $date)
	{

		return $query->whereBetween('date',[
					$date->startOfMonth()->toDateString(),
					$date->endOfMonth()->toDateString()
				]);
	}

	public function scopeFindByProductId($query, $value)
	{
		return $query->where('product_id', '=', $value);
	}

	public function scopeFindByStockNumber($query, $value)
	{
		return $query->where('stocknumber', '=', $value);
	}

	public function scopeFilterByIssued($query)
	{
		return $query->where('issued','>',0);
	}

	public function scopeFilterByReceived($query)
	{
		return $query->where('received','>',0);
	}
	/*
	*
	*	Referencing to Supply Table
	*	One-to-many attribute
	*
	*/

	public function setBalance()
	{
		$stockcard = StockCard::where('product_id','=',$this->product_id)
								->orderBy('date','desc')
								->orderBy('created_at','desc')
								->orderBy('id','desc')
								->first();

		if(!isset($this->received))
		{
			$this->received = 0;
		}

		if(!isset($this->issued))
		{
			$this->issued = 0;
		}

		$this->balance = (isset($stockcard->balance) ? $stockcard->balance : 0) + ( $this->received - $this->issued ) ;
	}

	/*
	*
	*	Call this function when receiving an item
	*
	*/
	public function receipt()
	{
		$firstname = Auth::user()->firstname;
		$middlename =  Auth::user()->middlename;
		$lastname = Auth::user()->lastname;
		$fullname =  $firstname . " " . $middlename . " " . $lastname;
		$supplier = null;

		// if(isset($this->organization))
		// {
		// 	$supplier = Supplier::firstOrCreate([ 'name' => $this->organization ]);
		// }

		// if(isset($this->receipt) && $this->receipt != null)
		// {

		// 	$receipt = Receipt::firstOrCreate([ 
		// 		'number' => $this->receipt 
		// 	], [
		// 		'reference' => isset($this->reference) ? $this->reference : null,
		// 		'date_delivered' => Carbon\Carbon::parse($this->date),
		// 		'received_by' => $fullname,
		// 		'supplier_name' => $this->organization
		// 	]);

		// 	$supply = ReceiptSupply::firstOrNew([
		// 		'receipt_number' => $receipt->number,
		// 		'stocknumber' => $this->stocknumber
		// 	]);


		// 	$supply->remaining_quantity = (isset($supply->remaining_quantity) ? $supply->remaining_quantity : 0) + $this->received;
		// 	$supply->quantity = (isset($supply->quantity) ? $supply->quantity : 0) + $this->received;
		// 	$supply->stocknumber = $this->stocknumber;
		// 	$supply->save();
			
		// }

		$this->setBalance();
		$this->save();
	}


	/*
	*
	*	Call this function when releasing
	*
	*/
	public function issue()
	{
		$firstname = Auth::user()->firstname;
		$middlename =  Auth::user()->middlename;
		$lastname = Auth::user()->lastname;
		$username =  $firstname . " " . $middlename . " " . $lastname;
		$this->setBalance();
		$this->save();

		// $receipt = ReceiptSupply::where('stocknumber','=',$this->stocknumber)
		// 										->where('remaining_quantity','>',0)
		// 										->get();

		// if(count($receipt) == 0)
		// {
		// 	$this->setBalance();
		// 	$this->save();
		// }
		// else
		// {

		// 	foreach($receipt as $receipt)
		// 	{
		// 		if($this->issued > 0)
		// 		{

		// 			if($receipt->remaining_quantity >= $this->issued)
		// 			{
		// 				$receipt->remaining_quantity = $receipt->remaining_quantity - $this->issued;
		// 				$this->setBalance();
		// 				$this->save();
		// 				$this->issued = 0;
		// 			}
		// 			else
		// 			{
		// 				$this->issued = $this->issued - $receipt->remaining_quantity;
		// 				$receipt->remaining_quantity = 0;
		// 				$this->setBalance();
		// 				$this->save();
		// 			}

		// 			$receipt->save();
		// 		}
		// 	}
		// }

	}

	public function transaction()
	{
		return $this->belongsTo('App\Transaction','id','id');
	}

	public function unit()
    {
      return $this->belongsTo('App\Product','product_id', 'id');
    }
}