<?php

namespace App\Http\Controllers;

use App;
use DB;
use Validator;
use Session;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $products = App\Product::with('category')->with('supply')->with('unit')->get();

            return datatables($products)->toJson();
        }

        return view('maintenance.product.index')
            ->with('title','Products');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = App\Category::pluck('name', 'id');
        $supply = [ '' => 'None' ] + App\Supply::pluck('name', 'id')->toArray();
        $unit = [ '' => 'None' ] + App\Unit::pluck('name', 'id')->toArray();
        
        return view('maintenance.product.create')
                ->with('category', $category)
                ->with('supply', $supply)
                ->with('unit', $unit);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, App\Product $product)
    {
        $code = $this->sanitizeString($request->get('code'));
        $supply = $this->sanitizeString($request->get('supply'));
        $category = $this->sanitizeString($request->get('category'));
        $unit = $this->sanitizeString($request->get('unit'));
        $cost = $this->sanitizeString($request->get('cost'));

        ($supply == '') ? $supply = null : $supply;
        ($unit == '') ? $unit = null : $unit;
        ($supply == '' && $unit == '' && $cost == '') ? $cost = 0 : $cost;

        $validator = Validator::make([
            'code' => $code,
            'supply' => $supply,
            'category' => $category,
            'unit' => $unit,
            'cost' => $cost
        ],$product->rules());

        if($validator->fails())
        {
            return back()
                    ->withInput()
                    ->withErrors($validator);
        }

        $product->code = $code;
        $product->supply_id = $supply;
        $product->category_id = $category;
        $product->unit_id = $unit;
        $product->cost = (isset($cost) && $cost != '' && $cost != null) ? $cost : 0;
        $product->save();

        \Alert::success('Product Added')->flash();
        return redirect('maintenance/product');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
                
        $product = App\Product::find($id);

        if( count($product) <= 0 )
        {
            \Alert::error('Invalid product Credentials')->flash();
            return redirect('maintenance/product');
        }

        $category = App\Category::pluck('name', 'id');
        $supply = [ '' => 'None' ] + App\Supply::pluck('name', 'id')->toArray();
        $unit = [ '' => 'None' ] + App\Unit::pluck('name', 'id')->toArray();
        
        return view('maintenance.product.edit')
                ->with('category', $category)
                ->with('supply', $supply)
                ->with('unit', $unit)
                ->with('product', $product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $code = $this->sanitizeString($request->get('code'));
        $supply = $this->sanitizeString($request->get('supply'));
        $category = $this->sanitizeString($request->get('category'));
        $unit = $this->sanitizeString($request->get('unit'));
        $cost = $this->sanitizeString($request->get('cost'));

        ($supply == '') ? $supply = null : $supply;
        ($unit == '') ? $unit = null : $unit;
        ($supply == '' && $unit == '' && $cost == '') ? $cost = 0 : $cost;

        $product = App\Product::find($id);

        $validator = Validator::make([
            'code' => $code,
            'supply' => $supply,
            'category' => $category,
            'unit' => $unit,
            'cost' => $cost
        ],$product->updateRules());

        if($validator->fails())
        {
            return back()
                    ->withInput()
                    ->withErrors($validator);
        }

        $product->code = $code;
        $product->supply_id = $supply;
        $product->category_id = $category;
        $product->unit_id = $unit;
        $product->cost = (isset($cost) && $cost != '' && $cost != null) ? $cost : 0;
        $product->save();

        \Alert::success('Product Added')->flash();
        return redirect('maintenance/product');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        if($request->ajax())
        {
            $product = App\Product::find($id);

            if( count($product) <= 0 )
            {
                return json_encode('error');
            }

            $product->delete();

            return json_encode('success');

        }

        $product = App\Product::find($id);

        if( count($product) <= 0 )
        {
            \Alert::error('Invalid product Credentials')->flash();
            return redirect('maintenance/product');
        }

        $product->delete();

        \Alert::success('Product Removed')->flash();
        return redirect('maintenance/product');
    }
}
