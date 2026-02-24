<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Forecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'pseudonym',
        'mayor_candidate_1_id',
        'mayor_candidate_2_id',
        'mayor_runoff_winner_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mayorCandidate1(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'mayor_candidate_1_id');
    }

    public function mayorCandidate2(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'mayor_candidate_2_id');
    }

    public function mayorRunoffWinner(): BelongsTo
    {
        return $this->belongsTo(Candidate::class, 'mayor_runoff_winner_id');
    }

    public function seats(): HasMany
    {
        return $this->hasMany(ForecastSeat::class);
    }
}
