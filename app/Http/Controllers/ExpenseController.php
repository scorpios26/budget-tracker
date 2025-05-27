<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id', // use foreign key validation
            'date' => 'required|date',
        ]);

        Expense::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'amount' => $request->amount,
            'category_id' => $request->category_id,
            'date' => $request->date,
        ]);

        return redirect()->back()->with('success', 'Expense added successfully!');
    }

        public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
        ]);

        $expense = Expense::findOrFail($id);
        $expense->update($request->all());

        return redirect()->back()->with('success', 'Expense updated successfully.');
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->back()->with('success', 'Expense deleted successfully.');
    }

    public function exportCsv()
    {
        $fileName = 'expenses.csv';
        $expenses = Expense::with('category')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ];

        $columns = ['Title', 'Amount', 'Category', 'Date'];

        $callback = function() use ($expenses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($expenses as $expense) {
                fputcsv($file, [
                    $expense->title,
                    $expense->amount,
                    $expense->category ? $expense->category->name : 'N/A',
                    $expense->date,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

}
