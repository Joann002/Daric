<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasFactory;

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
        [$year, $month] = explode('-', $this->month);
        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        return Transaction::where('category_id', $this->category_id)
            ->where('type', 'expense')
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->whereHas('account', fn ($q) => $q->where('user_id', $this->user_id))
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
