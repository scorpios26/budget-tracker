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

        // Budget warning logic
        $budgetWarning = null;
        if ($budgetAmount > 0) {
            $percentUsed = $totalExpenses / $budgetAmount;
            if ($remaining <= 0) {
                $budgetWarning = 'You have reached or exceeded your budget!';
            } elseif ($percentUsed >= 0.9) {
                $budgetWarning = 'Warning: You are close to exceeding your budget.';
            }
        }

        // Fetch all categories
        $categories = Category::all();
        // Fetch all expenses with their categories
        $expenses = Expense::with('category')->paginate(10);
        // Fetch all budgets for the user
        $budgets = Budget::where('user_id', $userId)->orderBy('month', 'desc')->get();

        return view('dashboard', compact('budgetAmount', 'totalExpenses', 'remaining', 'categories', 'budgets', 'expenses', 'budgetWarning'));
    }
}

