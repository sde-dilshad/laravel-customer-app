<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class CustomerController extends Controller
{

    public function show(string $id)
    {
        $customer = Customer::findOrFail(id: $id);

        return Inertia::render('CustomerProfile', [
            'data' => $customer,
        ]);
    }

    public function create()
    {
        return Inertia::render('CustomerCreate');
    }

    public function edit(string $id)
    {
        $customer = Customer::findOrFail(id: $id);

        return Inertia::render('CustomerProfileEdit', [
            'data' => $customer,
        ]);
    }
}
