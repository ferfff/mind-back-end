<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index() {
        $accounts = Account::all();
        return response()->json([
            'status' => 'success',
            'account' => $accounts,
        ]);
    }

    public function show($id)
    {
        $account = Account::find($id);
        return response()->json([
            'status' => 'success',
            'account' => $account,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'customer' => 'required|string|max:255',
            'responsible' => 'required|int',
        ]);

        $account = Account::find($id);
        $account->name = $request->name;
        $account->customer = $request->customer;
        $account->responsible = $request->responsible;
        $account->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Todo updated successfully',
            'todo' => $todo,
        ]);
    }
}
