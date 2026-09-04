<?php declare(strict_types=1);

namespace LokiCheckout\Core\Recaptcha;

class FormId
{
    public const PLACE_ORDER = 'place_order';
    public const CUSTOMER_LOGIN = 'customer_login';
    public const NEWSLETTER = 'newsletter';
    public const COUPON_CODE = 'coupon_code';

    /**
     * @return string[]
     */
    public static function getAll(): array
    {
        return [
            self::PLACE_ORDER,
            self::CUSTOMER_LOGIN,
            self::NEWSLETTER,
            self::COUPON_CODE,
        ];
    }
}
