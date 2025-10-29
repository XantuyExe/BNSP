<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRecord extends Model
{
    protected $fillable = [
        'rental_id','admin_id','return_checked_at','cleaning_fee','damage_fee','deposit_refund','condition_note'
    ];
    protected $casts = ['return_checked_at'=>'datetime'];
    public function rental(): BelongsTo { return $this->belongsTo(Rental::class); }
    public function admin(): BelongsTo { return $this->belongsTo(User::class, 'admin_id'); }
}
