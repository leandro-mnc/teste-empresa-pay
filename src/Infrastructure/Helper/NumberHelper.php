<?php

namespace App\Infrastructure\Helper;

use NumberFormatter;

class NumberHelper
{
    public static function currencyFormat(float $amount)
    {
        $fmt = new NumberFormatter('pt_BR', NumberFormatter::CURRENCY);
        return $fmt->format($amount);
    }
}
