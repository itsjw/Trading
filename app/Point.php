<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{

    protected $fillable = [
        'date', 'value',
    ];

    protected $dates = [
        'date',
    ];

    public function scopeOfDay($query, Carbon $day)
    {

        return $query
            ->where('date', '>=', Carbon::create($day->year, $day->month, $day->day, 0, 0, 0))
            ->where('date', '<=', Carbon::create($day->year, $day->month, $day->day, 23, 59, 59))
            ->orderBy('date', 'asc');
    }

}
