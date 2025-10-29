<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    protected $fillable = ['rental_id','decided_at','final_price','note'];
    protected $casts = ['decided_at'=>'datetime'];
    public function rental(): BelongsTo { return $this->belongsTo(Rental::class); }
}
