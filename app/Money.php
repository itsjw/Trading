<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Money extends Model
{
    protected $fillable = [
        'value', 'date', 'type', 'currency',
    ];

    protected $dates = [
        'date',
    ];

    public function scopeHour($query)
    {
        $now = Carbon::now();

        return $query
            ->where('date', '>=', Carbon::create($now->year, $now->month, $now->day, $now->hour - 1, $now->minute, $now->second))
            ->where('date', '<=', Carbon::create($now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second))
            ->orderBy('date', 'asc');
    }

    public function scopeDay($query)
    {
        $now = Carbon::now();

        return $query
            ->where('date', '>=', Carbon::create($now->year, $now->month, $now->day - 1, $now->hour, $now->minute, $now->second))
            ->where('date', '<=', Carbon::create($now->year, $now->month, $now->day, $now->hour, $now->minute, $now->second))
            ->orderBy('date', 'asc');
    }

    public function scopeSell($query)
    {
        return $query->where('type', 'sell');
    }

    public function scopeSpot($query)
    {
        return $query->where('type', 'spot');
    }

    public function scopeBuy($query)
    {
        return $query->where('type', 'buy');
    }

}
