<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Party extends Model
{
    protected $fillable = ['name', 'short_name', 'color', 'logo_path'];

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function forecastSeats(): HasMany
    {
        return $this->hasMany(ForecastSeat::class);
    }
}
