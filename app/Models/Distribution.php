<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    use HasFactory;

    protected $fillable = [
        'beneficiary_id',
        'item_name',
        'quantity',
        'unit',
        'distributed_at',
        'notes'
    ];

    public function beneficiary()
    {
        return $this->belongsTo(Beneficiary::class);
    }
}