<?php

namespace App\Http\Controllers;

use App;
use DB;
use Validator;
use Session;
use Illuminate\Http\Request;

class ReferenceController extends Controller
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
            return datatables(App\Reference::all())->toJson();
        }

        return view('maintenance.reference.index')
            ->with('title','Reference');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('maintenance.reference.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, App\Reference $reference)
    {
        $type = $this->sanitizeString($request->get('type'));
        $lastname = $this->sanitizeString($request->get('lastname'));
        $firstname = $this->sanitizeString($request->get('firstname'));
        $middlename = $this->sanitizeString($request->get('middlename'));
        $company = $this->sanitizeString($request->get('company'));
        $address = $this->sanitizeString($request->get('address'));
        $contact = $this->sanitizeString($request->get('contact'));
        $email = $this->sanitizeString($request->get('email'));
        $description = $this->sanitizeString($request->get('description'));


        $validator = Validator::make([
            'type' => $type,
            'lastname' => $lastname,
            'firstname' => $firstname,
            'middlename' => $middlename,
            'company' => $company,
            'address' => $address,
            'contact' => $contact,
            'email' => $email,
            'description' => $description
        ],$reference->rules());

        if($validator->fails())
        {
            return back()
                    ->withInput()
                    ->withErrors($validator);
        }

        $reference->type = $type;
        $reference->lastname = $lastname;
        $reference->firstname = $firstname;
        $reference->middlename = $middlename;
        $reference->company = $company;
        $reference->address = $address;
        $reference->contact = $contact;
        $reference->email = $email;
        $reference->description = $description;
        $reference->save();

        \Alert::success('reference Added')->flash();
        return redirect('maintenance/reference');
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
        $reference = App\Reference::find($id);

        if( count($reference) <= 0 )
        {
            \Alert::error('Invalid Reference Credentials')->flash();
            return redirect('maintenance/reference');
        }

        return view('maintenance.reference.edit')
                ->with('title','reference')
                ->with('reference',$reference);
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
        $type = $this->sanitizeString($request->get('type'));
        $lastname = $this->sanitizeString($request->get('lastname'));
        $firstname = $this->sanitizeString($request->get('firstname'));
        $middlename = $this->sanitizeString($request->get('middlename'));
        $company = $this->sanitizeString($request->get('company'));
        $address = $this->sanitizeString($request->get('address'));
        $contact = $this->sanitizeString($request->get('contact'));
        $website = $this->sanitizeString($request->get('website'));
        $email = $this->sanitizeString($request->get('email'));
        $description = $this->sanitizeString($request->get('description'));

        $reference = App\Reference::find($id);


        $validator = Validator::make([
        'type' => $type,
        'lastname' => $lastname,
        'firstname' => $firstname,
        'middlename' => $middlename,
        'company' => $company,
        'address' => $address,
        'contact' => $contact,
        'email' => $email,
        'description' => $description
        ],$reference->updateRules());

        if($validator->fails())
        {
            return back()
                    ->withInput()
                    ->withErrors($validator);
        }

        $reference->type = $type;
        $reference->lastname = $lastname;
        $reference->firstname = $firstname;
        $reference->middlename = $middlename;
        $reference->company = $company;
        $reference->address = $address;
        $reference->contact = $contact;
        $reference->email = $email;
        $reference->description = $description;
        $reference->save();

        \Alert::success('Reference Added')->flash();
        return redirect('maintenance/reference');
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
            $reference = App\Reference::find($id);

            if( count($reference) <= 0 )
            {
                return json_encode('error');
            }

            $reference->delete();

            return json_encode('success');

        }

        $reference = App\Reference::find($id);

        if( count($reference) <= 0 )
        {
            \Alert::error('Invalid Reference Credentials')->flash();
            return redirect('maintenance/reference');
        }

        $reference->delete();

        \Alert::success('reference Removed')->flash();
        return redirect('maintenance/reference');
    }
}
