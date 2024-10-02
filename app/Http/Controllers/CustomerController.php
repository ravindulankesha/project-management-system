<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Address;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('addresses')->get();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'addresses.*.street' => 'required|string|max:255',
            'addresses.*.city_state' => 'required|string|max:255',
            'addresses.*.number' => 'required|string|max:10',
        ]);

        $customer = Customer::create($request->only('name', 'email','company','phone','country'));

        foreach ($request->addresses as $address) {
            $customer->addresses()->create($address);
        }

        return redirect()->route('customers.index');
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'country' => 'required|string|max:255',
        ]);

        $customer->update($request->only('name', 'phone', 'email', 'country'));

        return response()->json([
            'message' => 'Customer updated successfully!',
            'customer' => $customer
        ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully!'
        ]);
    }
}

