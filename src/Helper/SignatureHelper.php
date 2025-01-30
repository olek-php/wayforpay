<?php

namespace Olek\WayForPay\Helper;

use Olek\WayForPay\Contract\SignatureAbleInterface;

class SignatureHelper
{
    public const FIELDS_DELIMITER  = ";";

    /**
     * @param array $fieldsValues
     * @param string $secret
     * @return string
     */
    public static function calculateSignature(array $fieldsValues, string $secret): string
    {
        $data = [];

        foreach ($fieldsValues as $value) {
            if ($value instanceof SignatureAbleInterface) {
                $data[] = $value->getConcatenatedString(self::FIELDS_DELIMITER);
            } elseif (is_array($value)) {
                $data[] = implode(self::FIELDS_DELIMITER, $value);
            } else {
                $data[] = (string)$value;
            }
        }

        return $data ?
            hash_hmac("md5", implode(self::FIELDS_DELIMITER, $data), $secret) :
            false;
    }
}