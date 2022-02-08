<?php
namespace App\Helpers;

class UnitsMultipliersHelper
{
    static function mergeArray(&$input, array $keys)
    {
        foreach ($keys as $key)
        {
            $configData = config('unitsmultipliers.' . $key);
            self::findAndReplaceStringInArray($input, $key, $configData);
        }
    }

    static function findAndReplaceStringInArray(&$input, $replacedKey, $configArray)
    {
        foreach ($input as $key => $value)
        {
            if (is_array($input[$key]))
            {
                if ($key === $replacedKey) {
                    foreach ($input[$key] as $innerKey => $innerValue)
                    {
                        if (array_key_exists($innerKey, $configArray)) {
                            $input[$key][$innerKey] = array_merge($input[$key][$innerKey], $configArray[$innerKey]);
                        }
                    }
                }
                self::findAndReplaceStringInArray($input[$key], $replacedKey, $configArray);
            }
        }
    }
}


