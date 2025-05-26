<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        try {
            Category::create([
                'name' => $request->name,
            ]);

            return redirect()->back()->with('success', 'Category added successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Category already exists!');
        }
    }
}
