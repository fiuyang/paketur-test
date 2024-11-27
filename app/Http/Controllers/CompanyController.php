<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query();

        $sort = $request->get('sort', 'asc');

        if (in_array($sort, ['asc', 'desc'])) {
            $query->orderBy('id', $sort);
        }

        $limit = $request->get('limit', 10); // Default limit is 10
        $page = $request->get('page', 1); // Default page is 1

        $companies = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json($companies);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role->name !== 'super_admin') {
            return response()->json([
                'message' => 'forbidden access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'email' => 'required|email|unique:companies,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $company = Company::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
        ]);

        $role = Role::where('name', 'manager')->first();

        User::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'password' => bcrypt('password'),
            'email' => $request->email,
            'company_id' => $company->id,
            'role' => $role->id
        ]);

        return response()->json([
            'message' => 'company successfully created'
        ], 201);
    }

    public function destroy($id)
    {
        if (auth()->user()->role->name !== 'super_admin') {
            return response()->json([
                'message' => 'forbidden access'
            ], 403);
        }

        $company = Company::find($id);
        if (!$company) {
            return response()->json([
                'message' => 'record not found'
            ], 404);
        }

        $company->delete();

        return response()->json([
            'message' => 'company successfully deleted'
        ]);
    }
}
