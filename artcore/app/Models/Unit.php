<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'category_id','code','name','vintage','sale_price','rent_price_5d',
        'is_available','is_sold','images','description'
    ];
    protected $casts = [
        'images'=>'array',
        'is_available'=>'boolean',
        'is_sold'=>'boolean',
    ];

    public function scopeStatusOrdering($query)
    {
        return $query->orderByRaw("
            CASE
                WHEN is_available = 1 AND COALESCE(is_sold,0) = 0 THEN 0
                WHEN is_available = 0 AND COALESCE(is_sold,0) = 0 THEN 1
                WHEN COALESCE(is_sold,0) = 1 THEN 2
                ELSE 3
            END
        ")->orderBy('name');
    }

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function rentals(): HasMany { return $this->hasMany(Rental::class); }

    public function isSculptureDoubleSlot(): bool {
        return $this->category?->name === 'SCULPTURE_3D';
    }
    public function requiresDeposit(): bool {
        return in_array($this->vintage, config('artcore.vintage_deposit', []));
    }
}
