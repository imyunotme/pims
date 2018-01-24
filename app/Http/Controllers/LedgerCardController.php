<?php
namespace App\Http\Controllers;

use App;
use Auth;
use Carbon;
use Session;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class LedgerCardController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $stocknumber)
	{
		if($request->ajax())
		{
			return json_encode([
				'data' => App\MonthlyLedgerCardView::findByStockNumber($stocknumber)
								->get()
			]);
		}

		$supply = App\Supply::find($stocknumber);

		return view('ledgercard.index')
				->with('supply',$supply)
				->with('title',$supply->stocknumber);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		$supplier = App\Supplier::pluck('name','name');
		return view('ledgercard.batch.accept')
				->with('supplier',$supplier)
				->with('title','Supply Ledger Card');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$purchaseorder = $this->sanitizeString(Input::get('purchaseorder'));
		$deliveryreceipt = $this->sanitizeString(Input::get('dr'));
		$date = $this->sanitizeString(Input::get('date'));
		$daystoconsume = $this->sanitizeString(Input::get("daystoconsume"));
		$stocknumbers = Input::get("stocknumber");
		$receiptquantity = Input::get("quantity");
		$receiptunitprice = Input::get("unitprice");
		$invoice = $this->sanitizeString(Input::get('invoice'));

		DB::beginTransaction();

		foreach($stocknumbers as $stocknumber)
		{
			$validator = Validator::make([
				'Stock Number' => $stocknumber,
				'Purchase Order' => $purchaseorder,
				'Date' => $date,
				'Receipt Quantity' => $receiptquantity["$stocknumber"],
				'Receipt Unit Price' => $receiptunitprice["$stocknumber"],
				'Days To Consume' => $daystoconsume
			], App\LedgerCard::$receiptRules);

			if($validator->fails())
			{
				DB::rollback();

				return redirect("inventory/supply/ledgercard/batch/form/accept")
						->with('total',count($stocknumbers))
						->with('stocknumber',$stocknumbers)
						->with('quantity',$receiptquantity)
						->with('unitprice',$receiptunitprice)
						->with('daystoconsume',$daystoconsume)
						->withInput()
						->withErrors($validator);
			}


			$transaction = new App\LedgerCard;
			$transaction->date = Carbon\Carbon::parse($date);
			$transaction->stocknumber = $stocknumber;
			$transaction->reference = $purchaseorder;
			$transaction->receipt = $deliveryreceipt;
			$transaction->invoice = $invoice;
			$transaction->receivedquantity = $receiptquantity["$stocknumber"];
			$transaction->receivedunitprice = $receiptunitprice["$stocknumber"];
			$transaction->issuedunitprice = $receiptunitprice["$stocknumber"];
			$transaction->daystoconsume = $daystoconsume;
			$transaction->created_by = Auth::user()->id;
			$transaction->receipt();
		}

		DB::commit();

		\Alert::success('Supplies Added')->flash();
		return redirect('inventory/supply');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id,$month)
	{

		if($request->ajax())
		{
			$transaction = StockCard::with('supply')->findByStockNumber($id)->get();
			return json_encode([ 'data' => $transaction ]);
		}

		$supply = App\Supply::find($id);

		$ledgercard = App\LedgerCard::filterByMonth($month)->findByStockNumber($id)->get();
		return view('ledgercard.show')
				->with('ledgercard',$ledgercard)
				->with('month',Carbon\Carbon::parse($month)->format('F Y'))
				->with('supply',$supply)
				->with('title',$supply->stocknumber);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return redirect("inventory/supply/$id/ledgercard");
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return redirect("inventory/supply/$id/ledgercard");
	}

	/**
	 * Show the form for releasing
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function releaseForm($id)
	{
		return View('ledgercard.batch.release')
				->with('title','Supply Ledger Card');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id=null)
	{
		$reference = $this->sanitizeString(Input::get('reference'));
		$date = $this->sanitizeString(Input::get('date'));
		$daystoconsume = $this->sanitizeString(Input::get("daystoconsume"));
		$stocknumbers = Input::get("stocknumber");
		$issuequantity = Input::get("quantity");
		$issueunitprice = Input::get("unitprice");

		DB::beginTransaction();

		foreach($stocknumbers as $stocknumber)
		{
			$validator = Validator::make([
				'Stock Number' => $stocknumber,
				'Requisition and Issue Slip' => $reference,
				'Date' => $date,
				'Issue Quantity' => $issuequantity["$stocknumber"],
				'Issue Unit Price' => $issueunitprice["$stocknumber"],
				'Days To Consume' => $daystoconsume
			],App\LedgerCard::$issueRules);

			if($validator->fails())
			{
				DB::rollback();

				return redirect("inventory/supply/ledgercard/batch/form/release")
						->with('total',count($stocknumbers))
						->with('stocknumber',$stocknumbers)
						->with('quantity',$issuequantity)
						->with('unitprice',$issueunitprice)
						->with('daystoconsume',$daystoconsume)
						->withInput()
						->withErrors($validator);
			}
		}

		$transaction = new App\LedgerCard;
		$transaction->date = Carbon\Carbon::parse($date);
		$transaction->stocknumber = $stocknumber;
		$transaction->reference = $reference;
		$transaction->issuedquantity = $issuequantity["$stocknumber"];
		$transaction->receivedunitprice = $issueunitprice["$stocknumber"];
		$transaction->issuedunitprice = $issueunitprice["$stocknumber"];
		$transaction->daystoconsume = $daystoconsume;
		$transaction->created_by = Auth::user()->id;
		$transaction->issue();

		DB::commit();

		\Alert::success('Supplies Released')->flash();
		return redirect('inventory/supply');
	}

	public function checkIfLedgerCardExists()
	{
		if($request->ajax())
		{
			$quantity = $this->sanitizeString(Input::get('quantity'));
			$reference = $this->sanitizeString(Input::get('reference'));
			$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
			$month = $this->sanitizeString(Input::get('date'));
			return json_encode(App\LedgerCard::isExistingRecord($reference,$stocknumber,$quantity,$month));
		}
	}

	public function printAllLedgerCard()
	{
		$supplies = App\Supply::all();
		$data = [
			'supplies' => $supplies
		];

		$filename = "App\LedgerCard-".Carbon\Carbon::now()->format('mdYHm').".pdf";
		$view = "ledgercard.print_all_index";

		return $this->printPreview($view,$data,$filename);

	}

	public function printSummaryLedgerCard($stocknumber)
	{
		$ledgercard = App\MonthlyLedgerCardView::findByStockNumber($stocknumber)
								->get();

		$supply = App\Supply::find($stocknumber);

		$data = ['supply' => $supply, 'ledgercard' => $ledgercard ];

		$filename = "App\LedgerCardSummary-".Carbon\Carbon::now()->format('mdYHm')."-$stocknumber.pdf";
		$view = "ledgercard.print_index";

		return $this->printPreview($view,$data,$filename);

	}

	public function printLedgerCard($stocknumber)
	{

		$ledgercard = App\LedgerCard::findByStockNumber($stocknumber)->get();
		$supply = App\Supply::find($stocknumber);

		$data = ['supply' => $supply, 'ledgercard' => $ledgercard ];

		$filename = "App\LedgerCard-".Carbon\Carbon::now()->format('mdYHm')."-$stocknumber.pdf";
		$view = "ledgercard.print_show";

		return $this->printPreview($view,$data,$filename);

	}

	public function computeCost(Request $request, $type)
	{
		if($request->ajax())
		{
			$stocknumber = $this->sanitizeString(Input::get('stocknumber'));
			$quantity = $this->sanitizeString(Input::get('quantity'));
			if($type == 'fifo')
			{

				$supply = App\ReceiptSupply::findByStockNumber($stocknumber)
										->join('receipts','number','=','receipts_supplies.receipt_number')
										->orderBy('date_delivered','desc')
										->where('remaining_quantity','>',0)
										->select('cost')
										->pluck('cost');

				if(count($supply) > 0)
				{
					return json_encode($supply);	
				}

			}
			else
			{

				$supply = App\ReceiptSupply::findByStockNumber($stocknumber)
										->where('remaining_quantity','>',0)
										->select('cost')
										->pluck('cost');

				if(count($supply) > 0)
				{
					$total = $supply->sum();
					$count = count($supply);

					return json_encode($total/$count);
				}
			}

			return json_encode(0);
		}
	}

	public function showUncopiedRecords(Request $request)
	{
		if($request->ajax())
		{
			return datatables(
				App\StockCard::doesntHave('transaction')
					->select('id', 'date', 'reference', 'receipt', 'organization', 'received', 'issued', 'stocknumber')
					->get()
			)->toJson();
		}

		return view('record.uncopied');
	}

	public function copy(Request $request)
	{
		$unitprice = $request->get('unitprice');
		$fundcluster = $request->get('fundcluster');
		$record = $request->get('record');
		$issued = $record['issued'];
		$organization = $record['organization'];
		$received = $record['received'];
		$stocknumber = $record['stocknumber'];
		$reference = $record['reference'];
		$receipt = $record['receipt'];
		$date = $record['date'];
		$daystoconsume = "";

		$transaction = App\LedgerCard::where('date','=', $date)
										->where('reference','=', $reference)
										->where('receipt','=', $receipt)
										->where('receivedunitprice','=', $unitprice)
										->where('issuedunitprice','=', $unitprice)
										->where('stocknumber','=', $stocknumber)
										->get();

		if(count($transaction) > 0) return json_encode('duplicate');

		DB::beginTransaction();

		$transaction = new App\LedgerCard;
		$transaction->date = Carbon\Carbon::parse($date);
		$transaction->stocknumber = $stocknumber;
		$transaction->reference = $reference;
		$transaction->receipt = $receipt;
		$transaction->fundcluster = $fundcluster;
		$transaction->receivedunitprice = $unitprice;
		$transaction->issuedunitprice = $unitprice;
		$transaction->daystoconsume = $daystoconsume;
		$transaction->created_by = Auth::user()->id;

		if($issued > 0 && $issued != null):

			$validator = Validator::make([
				'Stock Number' => $stocknumber,
				'Requisition and Issue Slip' => $reference,
				'Date' => $date,
				'Issue Quantity' => $issued,
				'Issue Unit Price' => $unitprice,
				'Days To Consume' => $daystoconsume
			],App\LedgerCard::$issueRules);

			if($validator->fails())
			{
				DB::rollback();

				return json_encode('error');
			}

			$transaction->issuedquantity = $issued;
			$transaction->issue();
		endif;

		if($received > 0 && $received != null):

			$validator = Validator::make([
				'Stock Number' => $stocknumber,
				'Purchase Order' => $purchaseorder,
				'Date' => $date,
				'Receipt Quantity' => $received,
				'Receipt Unit Price' => $unitprice,
				'Days To Consume' => $daystoconsume
			], App\LedgerCard::$receiptRules);

			if($validator->fails())
			{
				DB::rollback();

				return json_encode('error');
			}

			$transaction->receivedquantity = $received;
			$transaction->receipt();
		endif;

		DB::commit();

		return json_encode('success');
	}

}
