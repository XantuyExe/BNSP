<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penalty extends Model
{
    protected $fillable = ['rental_id','kind','amount','reason'];
    public function rental(): BelongsTo { return $this->belongsTo(Rental::class); }
}
