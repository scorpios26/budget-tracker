<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $budget = Budget::where('user_id', $userId)->latest()->first();

        $totalExpenses = Expense::where('user_id', $userId)->sum('amount');
        $budgetAmount = Budget::where('user_id', $userId)->sum('amount');
        // $budgetAmount = $budget?->amount ?? 0;
        $remaining = $budgetAmount - $totalExpenses;

        // Fetch all categories
        $categories = Category::all();
        // Fetch all expenses with their categories
        $expenses = Expense::with('category')->paginate(10);
        // Fetch all budgets for the user
        $budgets = Budget::where('user_id', $userId)->orderBy('month', 'desc')->get();

        return view('dashboard', compact('budgetAmount', 'totalExpenses', 'remaining', 'categories', 'budgets', 'expenses'));
    }
}

