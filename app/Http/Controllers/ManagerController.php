<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->role->name, ['manager', 'super_admin'])) {
            return response()->json([
                'message' => 'forbidden access'
            ], 403);
        }

        $query = User::with(['role', 'company'])->whereHas('role', function ($query) {
            $query->where('name', 'manager');
        });

        $sort = $request->get('sort', 'asc');

        if (in_array($sort, ['asc', 'desc'])) {
            $query->orderBy('id', $sort);
        }

        $limit = $request->get('limit', 10); // Default limit is 10
        $page = $request->get('page', 1); // Default page is 1

        $managers = $query->paginate($limit, ['*'], 'page', $page);

        return response()->json($managers);
    }

    public function show($id)
    {
        if (!in_array(auth()->user()->role->name, ['manager', 'super_admin'])) {
            return response()->json([
                'message' => 'forbidden access'
            ], 403);
        }

        $user = User::with(['role', 'company'])->find($id);
        if (!$user) {
            return response()->json([
                'message' => 'record not found'
            ], 404);
        }

        return response()->json([
            'data' => $user
        ]);
    }

}
