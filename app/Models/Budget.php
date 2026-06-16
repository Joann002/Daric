<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'month',
        'limit_amount',
    ];

    protected $casts = [
        'limit_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getSpentAmountAttribute()
    {
        return Transaction::where('category_id', $this->category_id)
            ->whereRaw("DATE_FORMAT(date, '%Y-%m') = ?", [$this->month])
            ->where('type', 'expense')
            ->sum('amount');
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->limit_amount == 0) {
            return 0;
        }
        return min(($this->spent_amount / $this->limit_amount) * 100, 100);
    }
}
