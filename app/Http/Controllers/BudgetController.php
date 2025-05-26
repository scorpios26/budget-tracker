<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
        'amount' => 'required|numeric',
        'month' => 'required|regex:/^\d{4}-\d{2}$/', // YYYY-MM format
     ]);

        Budget::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'month' => $request->month,
        ]);

        return redirect()->back()->with('success', 'Budget added successfully!');
    }
}
