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

            if (method_exists($this, $methodName)){

                $this->$methodName($arg);

            }else if (property_exists($this, $key)) {

                $this->$key = $this->formatField($key, $arg);

            }

        }
    }

    protected function formatField($key, $value)
    {
        if (! empty($value)) {

            $func = "format_{$key}";

            if (method_exists(Helper::class, $func)) {

                return Helper::$func($value);

            }

        }

        return $value;
    }
}
