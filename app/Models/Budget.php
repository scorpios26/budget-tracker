<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    /**
     * Get the user that owns the Budget
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'foreign_key', 'other_key');
    }

    /**
     * Get the expenses for the budget.
     */
    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    protected $fillable = ['user_id', 'title', 'amount', 'month'];
}
