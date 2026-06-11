<?php

namespace Omnipay\Tosla\Models;

use Omnipay\Tosla\Helpers\Helper;

class BaseModel
{
    public function __construct(?array $abstract)
    {
        foreach ($abstract as $key => $arg) {

            $methodName = 'set' . str_replace('_', '', $key);

            $key = str_replace('-', '_', $key);

            if (method_exists($this, $methodName)) {

                $this->$methodName($arg);

            } elseif (property_exists($this, $key)) {

                $this->$key = $this->formatField($key, $arg);

            }

        }
    }

    protected function formatField($key, $value)
    {
        if (! empty($value)) {

            $func = "format_{$key}";

            // Match the helper case-sensitively. PHP method resolution is
            // case-insensitive, so without this guard a PascalCase response
            // field such as "Amount" would collide with the request-side
            // "format_amount" helper and get scaled by 100 a second time.
            if (in_array($func, get_class_methods(Helper::class), true)) {

                return Helper::$func($value);

            }

        }

        return $value;
    }
}
