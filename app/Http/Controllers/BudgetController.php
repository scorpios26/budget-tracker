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
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'month' => 'required|regex:/^\d{4}-\d{2}$/', // YYYY-MM format
        ]);

        Budget::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'amount' => $request->amount,
            'month' => $request->month,
        ]);

        return redirect()->back()->with('success', 'Budget added successfully!');
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();
        // Persist showTable state to session so dashboard shows Budgets table after delete
        return redirect()->route('dashboard')->with(['success' => 'Budget deleted successfully!', 'showTable' => 'budget']);
    }

    public function update(Request $request, Budget $budget)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'month' => 'required|regex:/^\d{4}-\d{2}$/',
        ]);
        $budget->update([
            'title' => $request->title,
            'amount' => $request->amount,
            'month' => $request->month,
        ]);
        // Persist showTable state to session so dashboard shows Budgets table after update
        return redirect()->route('dashboard')->with(['success' => 'Budget updated successfully!', 'showTable' => 'budget']);
    }
}
