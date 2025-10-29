<?php

namespace App\Services;

use App\Models\{Rental, Unit};

class PricingService
{
    public function calcDeposit(Unit $unit): int
    {
        if (!$unit->requiresDeposit()) return 0;
        $percent = (int) config('artcore.deposit_percent', 30);
        return (int) round(($unit->sale_price * $percent) / 100);
    }

    public function calcLateFee(Rental $rental, \DateTimeInterface $actual): int
    {
        $plan = $rental->rental_end_plan;
        if (!$plan || $actual <= $plan) {
            return 0;
        }

        $percent = (int) config('artcore.late_fee_percent', 10);
        $baseAmount = $rental->rent_fee_paid ?: $rental->unit?->rent_price_5d ?: 0;

        return (int) round(($baseAmount * $percent) / 100);
    }

    public function trialToOwnFinalPrice(Unit $unit, Rental $rental): int
    {
        // harga akhir = sale_price - rent_fee_paid (deposit di-refund jika tak ada potongan)
        return max(0, (int) $unit->sale_price - (int) $rental->rent_fee_paid);
    }
}
