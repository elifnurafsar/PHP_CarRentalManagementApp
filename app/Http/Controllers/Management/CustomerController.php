<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customer;
use App\Car;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::paginate(10);
        return view('Management.customer')->with('customers', $customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cars = Car::all();
        return view('management.createCustomer')->with('cars', $cars);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'phone' => 'required|min:10|max:11',
            'license_no' => 'required|max:255',
            'date_of_birth' => 'required'
        ]);
        $customer = new Customer();
        $customer->name = $request->name;
        $customer->surname = $request->surname;
        $customer->phone = $request->phone;
        $customer->license_no = $request->license_no;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->save();
        $request->session()->flash('status','Driver '.$request->name." ".$request->surname.' has created successfully!');
        return redirect('management/customer');
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
        $customer = Customer::find($id);
        return view('management.editCustomer')->with('customer', $customer);
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
        $request->validate([
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'phone' => 'required|min:10|max:11',
            'license_no' => 'required|max:255',
            'date_of_birth' => 'required'
            
        ]);
        $customer = Customer::find($id);
        $customer->name = $request->name;
        $customer->surname = $request->surname;
        $customer->phone = $request->phone;
        $customer->license_no = $request->license_no;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->save();
        $request->session()->flash('status', 'The driver has updated successfully!');
        return redirect('/management/customer');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        $name = $customer->name;
        $surname = $customer->surname;
        Customer::destroy($id);
        Session()->flash('status', "The customer ".$name." ".$surname." has deleted successfully!");
        return redirect('/management/customer');
    }
}
