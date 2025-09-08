<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TimeLogs extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'clock_in',
        'clock_out',
        'total_hours',
        'date'
    ];

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'date' => 'date',
        'total_hours' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateTotalHours(): void
    {
        if ($this->clock_in && $this->clock_out) {
            $clockIn = Carbon::parse($this->clock_in);
            $clockOut = Carbon::parse($this->clock_out);
            $this->total_hours = $clockOut->diffInMinutes($clockIn) / 60;
            $this->save();
        }
    }

    public static function getTodaysLog($userId): ?TimeLogs
    {
        return static::where('user_id', $userId)
            ->whereDate('date', today())
            ->first();
    }

    public function isActive(): bool
    {
        return $this->clock_in && !$this->clock_out;
    }
}
