<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    { 
        $companies = Company::get();
        // $invoice = \App\Models\Invoice::find(1);
        // dd($invoice->company);
        //
        //\Auth::user()->assignRole('Super Admin');
        $companies = Company::paginate(5);
        return view ('company.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('company.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:4',
            'email' => 'required|email|unique:companies',
            'mobile' => 'required|digits:10',
        ]);
        Company::create($validated);
        return redirect(route('company.index'))->with('alert-success','Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return 'sdas';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
        return view('company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        //
        $validated = $request->validate([
            'name' => 'required|min:4',
            'email' => 'required|email|unique:companies,email,'.$company->id,
            'mobile' => 'required|digits:10',
        ]);
        $company->update($validated);
        return redirect(route('company.index'))->with('alert-success','Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function delete(Company $company)
    {
        $company->delete();
        return redirect(route('company.index'))->with('alert-success','Deleted Successfully');
    }
}
