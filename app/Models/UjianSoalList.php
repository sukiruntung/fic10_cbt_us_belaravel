<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UjianSoalList extends Model
{
    use HasFactory;

    protected $fillable = [
        'ujians_id',
        'soals_id',
        'kebenaran'
    ];

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujian::class);
    }
    // public function soal(): BelongsTo
    // {
    //     return $this->belongsTo(Soal::class);
    // }
    public function soals()
    {
        return $this->belongsTo(Soal::class);
    }
}
