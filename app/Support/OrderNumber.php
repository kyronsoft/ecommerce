<?php

namespace App\Support;

class OrderNumber
{
    public static function format(int $orderId): string
    {
        return 'ORD-'.str_pad((string) $orderId, 6, '0', STR_PAD_LEFT);
    }
}
