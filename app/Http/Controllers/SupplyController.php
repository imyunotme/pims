<?php
namespace App\Http\Controllers;

use App;
use Carbon;
use Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class SupplyController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax())
		{
			return datatables(App\Supply::all())->toJson();
		}
		return view('maintenance.supply.index')
                ->with('title','Supply Maintenance');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		return view('maintenance.supply.create')
				->with('unit',App\Unit::pluck('name','name'))
                ->with('title','Supply Maintenance');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$name = $this->sanitizeString($request->get('name'));
		$details = $this->sanitizeString($request->get('details'));

		$validator = Validator::make([
			'name' => $name,
			'details' => $details
		],App\Supply::$rules);

		if($validator->fails())
		{
			return redirect('maintenance/supply/create')
					->withInput()
					->withErrors($validator);
		}

		$supply = new App\Supply;
		$supply->name = $name;
		$supply->details = $details;
		$supply->save();

		\Alert::success('Supply added to inventory')->flash();
		return redirect('maintenance/supply');
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

			$supply = App\Supply::find($id);
			return json_encode([ 'data' => $supply ]);
		}
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$supply = App\Supply::find($id);
		return view('maintenance.supply.edit')
				->with('supply',$supply)
				->with('unit',App\Unit::pluck('name','name'))
        ->with('title','Supply Maintenance');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request,  $id)
	{
		$name = $this->sanitizeString($request->get('name'));
		$details = $this->sanitizeString($request->get('details'));
		$supply = App\Supply::find($id);

		$validator = Validator::make([
			'name' => $name,
			'details' => $details
		],$supply->updateRules());

		if($validator->fails())
		{
			return redirect("maintenance/supply/$id/edit")
					->withInput()
					->withErrors($validator);
		}
		
		$supply->name = $name;
		$supply->details = $details;
		$supply->save();

		\Alert::success('Supply information update')->flash();
		return redirect('maintenance/supply');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		if($request->ajax())
		{
			$supply = App\Supply::find($id);
			$supply->delete();
			return json_encode('success');
		}

		$supply = App\Supply::find($id);

		if(count($supply) <= 0 )
		{
		
			\Alert::error('Problem Encountered While Processing Your Data')->flash();
			return redirect()->back();
		}

		$supply->delete();

		\Alert::success('Supply Removed')->flash();

		return redirect('maintenance/supply');
	}


}
