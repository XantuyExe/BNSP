<?php

return [
    'max_rental_days'    => 5,
    'late_fee_percent'   => (int) env('ARTCORE_LATE_FEE_PERCENT', 10),
    'cleaning_flat_fee'  => (int) env('ARTCORE_CLEANING_FLAT_FEE', 150000),
    'deposit_percent'    => 30,   // untuk 60s/70s
    'vintage_deposit'    => ['60s','70s'],
    'tpo_window_days'    => 5,    // Trial-to-Own window
];
