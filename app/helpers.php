<?php

function CheckForDiscount($amount, $plan)
{
    $disc = session('discount', 0);
    $d_type = session('discount_type', 0);

    if ($disc == 0) {
        return $amount;
    }

    if ($plan == "monthly" && $d_type == 2) {
        return $amount;
    }

    if ($plan == "yearly" && $d_type == 1) {
        return $amount;
    }

    $dis = ($disc / 100) * $amount;

    return $amount - $dis;
}

function DiscountActive($plan): bool
{
    $disc = session('discount', 0);
    $d_type = session('discount_type', 0);

    if ($disc == 0) {
        return false;
    }

    if ($plan == "monthly" && $d_type == 2) {
        return false;
    }

    if ($plan == "yearly" && $d_type == 1) {
        return false;
    }

    return true;
}
