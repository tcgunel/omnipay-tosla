<?php

namespace Omnipay\Tosla\Constants;

class Currency
{
    public const TRY = 949;

    public static function list(): array
    {
        return [
            'TRY' => self::TRY,
        ];
    }
}
