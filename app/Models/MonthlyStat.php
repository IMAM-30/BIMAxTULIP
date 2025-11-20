<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyStat extends Model
{
    protected $fillable = ['year','month','value','label','order'];

    protected $casts = [
        'year' => 'integer',
        'month' => 'integer',
        'value' => 'integer',
        'order' => 'integer',
    ];

    // helper: readable month name
    public function getMonthNameAttribute()
    {
        return \Carbon\Carbon::createFromDate($this->year, $this->month, 1)->translatedFormat('F');
    }
}
