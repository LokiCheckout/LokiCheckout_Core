<?php declare(strict_types=1);

namespace LokiCheckout\Core\Recaptcha;

class Type
{
    public const CHECKBOX = 'recaptcha';
    public const INVISIBLE = 'invisible';
    public const V3 = 'recaptcha_v3';

    private const CONFIG_GROUPS = [
        self::CHECKBOX => 'type_recaptcha',
        self::INVISIBLE => 'type_invisible',
        self::V3 => 'type_recaptcha_v3',
    ];

    /**
     * @return string[]
     */
    public static function getAll(): array
    {
        return array_keys(self::CONFIG_GROUPS);
    }

    public static function isValid(string $type): bool
    {
        return array_key_exists($type, self::CONFIG_GROUPS);
    }

    public static function isInvisible(string $type): bool
    {
        return in_array($type, [self::INVISIBLE, self::V3], true);
    }

    public static function getConfigGroup(string $type): ?string
    {
        return self::CONFIG_GROUPS[$type] ?? null;
    }
}
