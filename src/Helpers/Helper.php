<?php

namespace Omnipay\Tosla\Helpers;

use Omnipay\Tosla\Constants\Currency;
use Omnipay\Tosla\Exceptions\OmnipayToslaCurrencyException;

class Helper
{
    public static function format_Bin($var)
    {
        return substr(preg_replace('/\D/', '', $var), 0, 6);
    }

    public static function format_amount($var)
    {
        return $var * 100;
    }

    public static function format_totalAmount($var)
    {
        return $var * 100;
    }

    public static function format_currency($var)
    {
        if (is_numeric($var)) {
            return $var;
        }

        if (! array_key_exists($var, Currency::list())) {
            throw new OmnipayToslaCurrencyException("Currency {$var} is not supported.");
        }

        return Currency::list()[$var];
    }

    public static function format_installmentCount($var)
    {
        if ((int) $var === 1) {
            return 0;
        }

        return $var;
    }

    public static function prettyPrint($data)
    {
        echo '<pre>'.print_r($data, true).'</pre>';
    }
}
