<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForecastSeat extends Model
{
    protected $fillable = ['forecast_id', 'party_id', 'seats'];

    public function forecast(): BelongsTo
    {
        return $this->belongsTo(Forecast::class);
    }

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }
}
