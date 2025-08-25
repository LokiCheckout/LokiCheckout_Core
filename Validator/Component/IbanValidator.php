<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator\Component;

use Loki\Components\Component\ComponentInterface;
use Loki\Components\Validator\ValidatorInterface;

class IbanValidator implements ValidatorInterface
{
    public function validate(mixed $value, ?ComponentInterface $component = null): bool|array
    {
        if (false === is_string($value)) {
            return true;
        }

        $iban = strtoupper(preg_replace('/[^A-Z0-9]/', '', $value ?? ''));

        if (!preg_match('/^([A-Z]{2})/', $iban)) {
            return [__('Your IBAN number needs to start with a country prefix')];
        }

        if (!preg_match('/^[A-Z]{2}[0-9]{2}[A-Z0-9]+$/', $iban)) {
            return [__('Your IBAN number does not have the right format')];
        }

        if (false === $this->validateIban($iban)) {
            return [__('Your IBAN number does not have the right length')];
        }

        return true;
    }

    private function validateIban(string $iban, bool $enforceCountryLength = true): bool
    {
        if ($enforceCountryLength) {
            $country = substr($iban, 0, 2);
            $lengths = [
                'AD' => 24,
                'AE' => 23, 'AL' => 28, 'AT' => 20, 'AZ' => 28, 'BA' => 20, 'BE' => 16,
                'BG' => 22,
                'BH' => 22,
                'BR' => 29,
                'BY' => 28, 'CH' => 21, 'CR' => 22, 'CY' => 28, 'CZ' => 24, 'DE' => 22, 'DK' => 18, 'DO' => 28,
                'EE' => 20, 'ES' => 24,
                'FI' => 18, 'FO' => 18, 'FR' => 27, 'GB' => 22, 'GE' => 22, 'GI' => 23, 'GL' => 18, 'GR' => 27,
                'GT' => 28, 'HR' => 21,
                'HU' => 28, 'IE' => 22, 'IL' => 23, 'IQ' => 23, 'IS' => 26, 'IT' => 27, 'JO' => 30, 'KW' => 30,
                'KZ' => 20, 'LB' => 28,
                'LC' => 32, 'LI' => 21, 'LT' => 20, 'LU' => 20, 'LV' => 21, 'MC' => 27, 'MD' => 24, 'ME' => 22,
                'MK' => 19, 'MR' => 27,
                'MT' => 31, 'MU' => 30, 'NL' => 18, 'NO' => 15, 'PK' => 24, 'PL' => 28, 'PS' => 29, 'PT' => 25,
                'QA' => 29, 'RO' => 24,
                'RS' => 22, 'SA' => 24, 'SC' => 31, 'SE' => 24, 'SI' => 19, 'SK' => 24, 'SM' => 27, 'ST' => 25,
                'SV' => 28, 'TL' => 23,
                'TN' => 24, 'TR' => 26, 'UA' => 29, 'VG' => 24, 'XK' => 20,
            ];
            if (isset($lengths[$country]) && strlen($iban) !== $lengths[$country]) {
                return false;
            }
        }

        $len = strlen($iban);
        if ($len < 15 || $len > 34) {
            return false;
        }

        $rearranged = substr($iban, 4) . substr($iban, 0, 4);

        $remainder = 0;
        $n = strlen($rearranged);
        for ($i = 0; $i < $n; $i++) {
            $ch = $rearranged[$i];
            if ($ch >= 'A' && $ch <= 'Z') {
                $val = (string)(ord($ch) - 55); // 'A' (65) -> 10
                $remainder = (($remainder * 10 + (int)$val[0]) % 97);
                if (isset($val[1])) { // 10..35 are two digits
                    $remainder = (($remainder * 10 + (int)$val[1]) % 97);
                }
            } else {
                $remainder = (($remainder * 10) + (int)$ch) % 97;
            }
        }

        return $remainder === 1;
    }
}
