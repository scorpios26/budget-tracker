<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['user_id', 'title', 'amount', 'category_id', 'date'];

    /**
     * Get the user that owns the expense.
     */
    public function user()
    {
        // Fix: remove 'foreign_key' and 'other_key' to use defaults
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category associated with the expense.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
