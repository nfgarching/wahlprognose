<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Candidate extends Model
{
    protected $fillable = ['name', 'party_id', 'photo_path'];

    public function party(): BelongsTo
    {
        return $this->belongsTo(Party::class);
    }
}
