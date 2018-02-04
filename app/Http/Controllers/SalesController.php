<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use DB;
use Validator;
use Carbon;

class SalesController extends Controller
{
    public function index(Request $request)
    {

        if($request->ajax())
        {
            $sales = App\Sale::with('product.category')->with('product.supply')->with('product.unit')->orderBy('date','desc')
                            ->get();
            return datatables($sales)->toJson();
        }

        return view('sale.index');
    }

    public function in(Request $request)
    {
        $customer = [ '' => 'None' ] + App\Reference::customer()->pluck('company', 'company')->toArray();
        $products = [ '' => 'None' ] + App\Product::get(['id', 'code', 'category_id', 'supply_id', 'unit_id'])
                    ->pluck('product_details', 'code')
                    ->toArray();

        return view('sale.in')
                ->with('products', $products)
                ->with('customer', $customer);  
    }

    public function store(Request $request)
    {

        $rows = $request->get('row');
        $customer = $this->sanitizeString($request->get('customer'));
        $date = $this->sanitizeString($request->get('date'));
        $product = $request->get('product');
        $quantity =$request->get('quantity');
        $amount = $request->get('amount');
        $reference = null;

        if($request->has('status'))
            $status = $this->sanitizeString($request->get('status'));
        else
            $status = 'received';

        $remarks = $this->sanitizeString($request->get('remarks'));

        DB::beginTransaction();

        foreach($rows as $row):

            $sale = new App\Sale;

            $validator = Validator::make([
                'product' => $product[$row],
                'reference' => $reference,
                'remarks' => $remarks
            ], $sale->inboundRules());

            $product = App\Product::findByCode( $product[$row] )->first();

            if(count($product) <= 0)
            {
                \Alert::error('Product must be created first before using the combination. Proceed to product tab for creation')->flash();
                DB::rollback();
                return back()
                        ->withInput()
                        ->withErrors($validator);
            }

            if($validator->fails())
            {
                DB::rollback();
                return back()
                        ->withInput()
                        ->withErrors($validator);
            }

            $sale->product_id = isset($product->id) ? $product->id : null;
            $sale->date = Carbon\Carbon::parse($this->sanitizeString($date))->format('Y-m-d');
            $sale->reference = $this->sanitizeString($customer);
            $sale->receipt = isset($receipt) ? $this->sanitizeString($receipt) : null;
            $sale->issued_amount = $this->sanitizeString( $amount[ "$row" ] );
            $sale->issued = ( isset($quantity[ "$row" ]) && $quantity[ "$row" ] != null && $quantity[ "$row" ] !='' ) ? $this->sanitizeString( $quantity[ "$row" ] ) : 0;
            $sale->remarks = $this->sanitizeString($remarks);
            $sale->status = $this->sanitizeString( $status );
            $sale->inbound();

        endforeach;

        DB::commit();

        \Alert::success("Transaction Completed")->flash();
        return redirect('sale');
    }

    public function out(Request $request)
    {
        $products = [ '' => 'None' ] + App\Product::get(['id', 'code', 'category_id', 'supply_id', 'unit_id'])
                    ->pluck('product_details', 'code')
                    ->toArray();
        $supplier = [ '' => 'None' ] + App\Reference::supplier()->pluck('company', 'company')->toArray();
        $type = 'out';
        $status = [ 'unpaid' => 'Unpaid', 'paid' => 'Paid' ];

        return view('sale.out')
                ->with('type', $type)
                ->with('status', $status)
                ->with('products', $products)
                ->with('supplier', $supplier); 
    }

    public function destroy(Request $request)
    {
        $rows = $request->get('row');
        $customer = $this->sanitizeString($request->get('customer'));
        $date = $this->sanitizeString($request->get('date'));
        $product = $request->get('product');
        $quantity =$request->get('quantity');
        $amount = $request->get('amount');
        $reference = null;

        if($request->has('status'))
            $status = $this->sanitizeString($request->get('status'));
        else
            $status = 'received';

        $remarks = $this->sanitizeString($request->get('remarks'));

        DB::beginTransaction();

        foreach($rows as $row):

            $sale = new App\Sale;

            return $product[$row];

            $validator = Validator::make([
                'product' => $product[$row],
                'reference' => $reference,
                'remarks' => $remarks
            ], $sale->inboundRules());

            $product = App\Product::findByCode( $product[$row] )->first();

            if($validator->fails())
            {
                DB::rollback();
                return back()
                        ->withInput()
                        ->withErrors($validator);
            }

            $sale->product_id = isset($product->id) ? $product->id : null;
            $sale->date = Carbon\Carbon::parse($this->sanitizeString($date))->format('Y-m-d');
            $sale->reference = $this->sanitizeString($customer);
            $sale->receipt = isset($receipt) ? $this->sanitizeString($receipt) : null;
            $sale->received_amount = $this->sanitizeString( $amount[ "$row" ] );
            $sale->received = ( isset($quantity[ "$row" ]) && $quantity[ "$row" ] != null && $quantity[ "$row" ] !='' ) ? $this->sanitizeString( $quantity[ "$row" ] ) : 0;
            $sale->remarks = $this->sanitizeString($remarks);
            $sale->status = $this->sanitizeString( $status );
            $sale->outbound();

        endforeach;

        DB::commit();

        \Alert::success("Transaction Completed")->flash();
        return redirect('sale');
    }

    public function printSale()
    {
        $sale = App\Sale::all();

        $data = [
            'sale' => $sale
        ];

        $filename = "InOut-".Carbon\Carbon::now()->format('mdYHm')."".".pdf";
        $view = "sale.print_index";

        return $this->printPreview($view,$data,$filename);
    }

    public function printAllSale()
    {
        $sale = App\Sale::find($sale);

        $data = [
            'sale' => $sale
        ];

        $filename = "InOut-".Carbon\Carbon::now()->format('mdYHm').".pdf";
        $view = "sale.print_all_index";
        return $this->printPreview($view,$data,$filename);
    }
}