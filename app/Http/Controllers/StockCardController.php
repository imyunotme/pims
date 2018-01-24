<?php
namespace App\Http\Controllers;

use App;
use Validator;
use Carbon;
use DB;
use Auth;
use PDF;
use Session;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class StockCardController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax())
		{
			if($request->has('product'))
			{
				$product = $this->sanitizeString($request->get('product'));

				return json_encode([
					'data' => App\Supply::findByStockNumber($product)
				]);
			}

			$supplies = App\Supply::all();
			return datatables($supplies)->toJson();
		}

		return view('inventory.supply.index')
                ->with('title','Supply Inventory');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$supplier = App\Supplier::pluck('name','name');
		return view('stockcard.batch.accept')
			->with('title','Accept')
			->with('supplier',$supplier);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$deliveryreceipt = $this->sanitizeString(Input::get('dr'));
		$date = $this->sanitizeString(Input::get('date'));
		$office = $this->sanitizeString(Input::get('office'));
		$supplier = $this->sanitizeString(Input::get("supplier"));
		$stocknumbers = Input::get("stocknumber");
		$quantity = Input::get("quantity");
		$unitcost = Input::get("unitcost");

		$username = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;

		$date = Carbon\Carbon::parse($date);

		DB::beginTransaction();

		foreach($stocknumbers as $stocknumber)
		{
			$validator = Validator::make([
				'Stock Number' => $stocknumber,
				'Date' => $date,
				'Quantity' => $quantity["$stocknumber"],
				'Unit Cost' => $unitcost["$stocknumber"],
				'Office' => $office
			],App\StockCard::$receiptRules);

			if($validator->fails())
			{
				DB::rollback();
				return redirect("inventory/supply/accept")
						->with('total',count($stocknumbers))
						->with('stocknumber',$stocknumbers)
						->with('quantity',$quantity)
						->with('unitcost',$unitcost)
						->withInput()
						->withErrors($validator);
			}

			$transaction = new App\StockCard;
			$transaction->date = Carbon\Carbon::parse($date);
			$transaction->stocknumber = $stocknumber;
			$transaction->receipt = $deliveryreceipt;
			$transaction->organization = $supplier;
			$transaction->received = $quantity["$stocknumber"];
			$transaction->unitcost = $unitcost["$stocknumber"];
			$transaction->status = 'received';
			$transaction->user_id = Auth::user()->id;
			$transaction->receipt();
		}

		DB::commit();

		\Alert::success('Supplies Received')->flash();
		return redirect('inventory/supply');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		if($request->ajax())
		{

			if($request->has('term'))
			{

				$stocknumber = $this->sanitizeString($request->get('term'));
				return App\Supply::where('stocknumber','like','%'.$stocknumber.'%')
								->pluck('stocknumber')
								->toJson();
			}

			return datatables(App\StockCard::findByProductId($id)->get())->toJson();
		}

		$product = App\Product::find($id);

		return View('stockcard.index')
				->with('product',$product)
				->with('title', isset($product->supply) ? isset($product->supply->name ) ? $product->supply->name : "None" : 'None' );
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return redirect("inventory/supply/$stocknumber/stockcard/$id/edit");
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		\Alert::success('Supply Updated')->flash();
		return redirect("inventory/supply/$stocknumber/stockcard");
	}


	/**
	 * Show the form for releasing
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function releaseForm(Request $request)
	{
		return view('stockcard.batch.release')
			->with('title','Release');
	}


	/**
	 * Release the supply.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id=null)
	{
		$reference = $this->sanitizeString(Input::get('reference'));
		$date = $this->sanitizeString(Input::get('date'));
		$office = $this->sanitizeString(Input::get('office'));
		$daystoconsume = $this->sanitizeString(Input::get("daystoconsume"));
		$stocknumber = Input::get("stocknumber");
		$quantity = Input::get("quantity");
		$unitcost = Input::get("unitcost");
		$status = Input::get("status");

		DB::beginTransaction();

		foreach($stocknumber as $_stocknumber)
		{
			$validator = Validator::make([
				'Stock Number' => $stocknumber,
				'Requisition and Issue Slip' => $reference,
				'Date' => $date,
				'Quantity' => $quantity["$_stocknumber"],
				'Unit Cost' => $unitcost["$_stocknumber"],
				'Office' => $office
			],App\StockCard::$issueRules);

			$balance = App\Supply::findByStockNumber($_stocknumber)->balance;
			if($validator->fails() || $quantity["$_stocknumber"] > $balance)
			{

				DB::rollback();

				if($quantity["$_stocknumber"] > $balance)
				{
					$validator = [ "You cannot release quantity of $_stocknumber which is greater than the remaining balance ($balance)" ];
				}

				return redirect("inventory/supply/release")
						->with('total',count($stocknumber))
						->with('stocknumber',$stocknumber)
						->with('quantity',$quantity)
						->with('daystoconsume',$daystoconsume)
						->withInput()
						->withErrors($validator);
			}

			$transaction = new App\StockCard;
			$transaction->date = Carbon\Carbon::parse($date);
			$transaction->stocknumber = $_stocknumber;
			$transaction->receipt = $reference;
			$transaction->organization = $office;
			$transaction->issued  = $quantity["$_stocknumber"];
			$transaction->unitcost = $unitcost["$_stocknumber"];
			$transaction->user_id = Auth::user()->id;
			$transaction->status = $status;
			$transaction->issue();
		}

		DB::commit();

		\Alert::success('Supplies Released')->flash();
		return redirect('inventory/supply');
	}

	public function printStockCard($stocknumber)
	{
		$supply = App\Supply::find($stocknumber);

		$data = [
			'supply' => $supply
		];

		$filename = "StockCard-".Carbon\Carbon::now()->format('mdYHm')."-$stocknumber".".pdf";
		$view = "stockcard.print_index";

		return $this->printPreview($view,$data,$filename);
	}

	public function printAllStockCard()
	{
		$supplies = App\Supply::all();

		$data = [
			'supplies' => $supplies
		];

		$filename = "StockCard-".Carbon\Carbon::now()->format('mdYHm').".pdf";
		$view = "stockcard.print_all_index";
		return $this->printPreview($view,$data,$filename);
	}

	public function generate(Request $request)
	{	
		$now = Carbon\Carbon::now();
		$const = $now->format('y') . '-' . $now->format('m');

		$id = count(App\StockCard::filterByIssued()->get()) + 1;

		if($request->ajax())
		{
			return json_encode( $const . '-' . $id ); 
		}

		return $const . '-' . $id;
	}

}
