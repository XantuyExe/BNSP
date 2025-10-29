<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};

class Rental extends Model
{
    protected $fillable = [
        'user_id','unit_id','status','rental_start','rental_end_plan','rental_end_actual',
        'deposit_required','deposit_paid','rent_fee_paid','eligibility_checked','notes','return_requested_at'
    ];
    protected $casts = [
        'rental_start'=>'datetime','rental_end_plan'=>'datetime','rental_end_actual'=>'datetime',
        'return_requested_at'=>'datetime','eligibility_checked'=>'boolean'
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function unit(): BelongsTo { return $this->belongsTo(Unit::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
    public function returnRecord(): HasOne { return $this->hasOne(ReturnRecord::class); }
    public function purchase(): HasOne { return $this->hasOne(Purchase::class); }
    public function penalties(): HasMany { return $this->hasMany(Penalty::class); }

    public function activeSlotCost(): int {
        return $this->unit?->isSculptureDoubleSlot() ? 2 : 1;
    }

    public function isReturnRequested(): bool
    {
        return $this->status === 'RETURN_REQUESTED';
    }
}
