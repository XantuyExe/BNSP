<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['rental_id','type','amount','method','paid_at','ref_code'];
    protected $casts = ['paid_at'=>'datetime'];
    public function rental(): BelongsTo { return $this->belongsTo(Rental::class); }
}
