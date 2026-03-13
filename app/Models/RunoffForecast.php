<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RunoffForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'pseudonym',
        'predicted_winner',
        'gruchmann_percent',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
