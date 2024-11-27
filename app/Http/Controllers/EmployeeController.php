<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->role->name, ['manager', 'employee'])) {
            return response()->json([
                'message' => 'forbidden access'
            ], 403);
        }

        $query = Employee::whereHas('user', function ($query) {
            $query->where('company_id', auth()->user()->company_id);
        });

        $sort = $request->get('sort', 'asc');

        if (in_array($sort, ['asc', 'desc'])) {
            $query->orderBy('id', $sort);
        }

        $limit = $request->get('limit', 10); // Default limit is 10
        $page = $request->get('page', 1); // Default page is 1

        $employees = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json($employees);
    }

    public function show($id)
    {
        if (!in_array(auth()->user()->role->name, ['manager', 'employee'])) {
            return response()->json([
                'message' => 'forbidden access'
            ], 403);
        }

        $employee = Employee::with('user')->find($id);
        if (!$employee) {
            return response()->json([
                'message' => 'record not found'
            ], 404);
        }

        return response()->json([
            'data' => $employee
        ]);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role->name !== 'manager') {
            return response()->json([
                'message' => 'forbidden access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15|unique:employees,phone_number',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        Employee::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'message' => 'employee successfully created'
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json([
                'message' => 'record not found'
            ], 404);
        }

        if (auth()->user()->role->name !== 'manager' && auth()->user()->id !== $employee->user_id) {
            return response()->json([
                'message' => 'forbidden access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15|unique:employees,phone_number,' . $employee->id,
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $employee->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        return response()->json([
            'message' => 'employee successfully updated'
        ], 200);
    }

    public function destroy($id)
    {
        if (auth()->user()->role->name !== 'manager') {
            return response()->json([
                'message' => 'forbidden access'
            ], 403);
        }

        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json([
                'message' => 'record not found'
            ], 404);
        }
        $employee->delete();
        return response()->json([
            'message' => 'employee successfully deleted'
        ]);
    }
}
