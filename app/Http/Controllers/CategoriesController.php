<?php

namespace App\Http\Controllers;

use App;
use Carbon;
use Session;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class CategoriesController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax())
		{
			return datatables(App\Category::all())->toJson();
		}
		return view('maintenance.category.index')
					->with('title','Category');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('maintenance.category.create')
					->with('title','Category');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

		$name = $this->sanitizeString($request->get('name'));

		$category = new App\Category;

		$validator = Validator::make([
			'name' => $name
		],$category->rules());

		if($validator->fails())
		{
			return redirect('maintenance/category/create')
				->withInput()
				->withErrors($validator);
		}

		$category->name = $name;
		$category->save();

		\Alert::success('Category added')->flash();
		return redirect('maintenance/category');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id = null)
	{
		if($request->ajax())
		{
			if($request->has('term'))
			{
				$code = $this->sanitizeString($request->get('term'));
				return json_encode( App\Category::where('code','like','%'.$code.'%')->pluck('code')->toArray());
			}

			return json_encode([
				'data' => App\Category::findByName($id)
			]);
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
		$category = App\Category::find($id);

		if(count($category) <= 0)
		{
			return view('errors.404');
		}

		return view("maintenance.category.edit")
				->with('category',$category)
				->with('title','Category');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$name = $this->sanitizeString($request->get('name'));

		$category = App\Category::find($id);

		$validator = Validator::make([
			'name' => $name
		],$category->updateRules());

		if($validator->fails())
		{
			return redirect("maintenance/category/$id/edit")
				->withInput()
				->withErrors($validator);
		}

		$category->name = $name;
		$category->save();

		\Alert::success('Category Information Updated')->flash();
		return redirect('maintenance/category');
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
			$category = App\Category::find($id);
			$category->delete();
			return json_encode('success');
		}

		try
		{
			$category = App\Category::find($id);
			$category->delete();
			\Alert::success('Category Removed')->flash();
		} catch (Exception $e) {
			\Alert::error('Problem Encountered While Processing Your Data')->flash();
		}

		return redirect('maintenance/category');
	}
}
