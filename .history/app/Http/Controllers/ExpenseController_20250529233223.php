<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id', // use foreign key validation
            'date' => 'required|date',
        ]);

        // Check if the selected budget has zero balance
        $budget = \App\Models\Budget::with('expenses')->find($request->budget_id);
        $totalExpenses = $budget->expenses->sum('amount');
        $remaining = $budget->amount - $totalExpenses;
        if ($remaining <= 0) {
            return redirect()->back()->withErrors('You cannot add an expense: the selected budget has zero balance.');
        }

        Expense::create([
            'user_id' => Auth::id(),
            'budget_id' => $request->budget_id,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Expense added successfully!');
    }

        public function update(Request $request, $id)
        {
            $request->validate([
                'budget_id' => 'required|exists:budgets,id',
                'amount' => 'required|numeric',
                'category_id' => 'required|exists:categories,id',
                'date' => 'required|date',
            ]);

            $expense = Expense::findOrFail($id);
            $expense->budget_id = $request->budget_id;
            $expense->amount = $request->amount;
            $expense->category_id = $request->category_id;
            $expense->date = $request->date;
            $expense->save();

            return redirect()->route('dashboard')->with('success', 'Expense updated successfully!');
        }
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->back()->with('success', 'Expense deleted successfully.');
    }

    public function exportCsv()
    {
        $expenses = Expense::with(['budget', 'category'])
            ->orderBy('date', 'desc')
            ->get();

        $filename = 'expenses_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $columns = ['Budget Title', 'Budget Amount', 'Expense', 'Category', 'Remaining Balance', 'Date'];

        $callback = function () use ($expenses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            $budgetRunningBalances = [];
            foreach ($expenses as $expense) {
                $budgetId = $expense->budget_id;
                if (!isset($budgetRunningBalances[$budgetId])) {
                    $budgetRunningBalances[$budgetId] = $expense->budget ? $expense->budget->amount : 0;
                }
                $remaining = $budgetRunningBalances[$budgetId] - $expense->amount;
                fputcsv($file, [
                    $expense->budget ? $expense->budget->title : 'N/A',
                    $expense->budget ? number_format($expense->budget->amount, 2) : 'N/A',
                    number_format($expense->amount, 2),
                    $expense->category ? $expense->category->name : 'N/A',
                    $expense->budget ? number_format($remaining, 2) : 'N/A',
                    \Carbon\Carbon::parse($expense->date)->format('F d, Y'),
                ]);
                $budgetRunningBalances[$budgetId] = $remaining;
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function chartPage()
    {
        $categories = \App\Models\Category::all();
        return view('expenses_chart', compact('categories'));
    }

    public function chartData(Request $request)
    {
        $categoryId = $request->query('category_id');
        $budgets = \App\Models\Budget::with(['expenses' => function($q) use ($categoryId) {
            if ($categoryId) {
                $q->where('category_id', $categoryId);
            }
        }])->get();
        $labels = [];
        $expenses = [];
        foreach ($budgets as $budget) {
            $labels[] = $budget->title;
            $expenses[] = $budget->expenses->sum('amount');
        }
        return response()->json([
            'labels' => $labels,
            'expenses' => $expenses,
        ]);
    }

}
