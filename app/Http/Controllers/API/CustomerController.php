<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        sleep(1);
        $customers = Customer::orderBy('id', 'desc')->paginate(20);
        return response()->json($customers);
    }


    public function show(string $id): JsonResponse
    {
        $customer = Customer::findorFail($id);

        if (!$customer) {
            return response()->json(data: ['message' => 'Customer not found'], status: 404);
        }

        return response()->json($customer);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'age' => 'required|integer|min:0|max:120',
            'dob' => 'required|date',
            'email' => 'required|email|unique:customers,email',
        ]);

        if ($validator->fails()) {
            return response()->json(data: ['errors' => $validator->errors()], status: 422);
        }

        $customer = Customer::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'age' => $request->age,
            'dob' => $request->dob,
            'email' => $request->email,
            'creation_date' => now(),
        ]);

        return response()->json(data: $customer, status: 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $customer = Customer::find(id: $id);

        if (!$customer) {
            return response()->json(data: ['message' => 'Customer not found'], status: 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:50',
            'last_name' => 'sometimes|required|string|max:50',
            'age' => 'sometimes|required|integer|min:0|max:120',
            'dob' => 'sometimes|required|date',
            'email' => 'sometimes|required|email|unique:customers,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(data: ['errors' => $validator->errors()], status: 422);
        }

        $customer->update($request->only([
            'first_name',
            'last_name',
            'age',
            'dob',
            'email'
        ]));

        sleep(2);
        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(data: ['message' => 'Customer not found'], status: 404);
        }

        $customer->delete();

        return response()->json(data: ['message' => 'Customer deleted successfully']);
    }
}
