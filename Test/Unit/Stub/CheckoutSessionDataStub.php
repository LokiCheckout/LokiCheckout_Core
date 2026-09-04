<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Stub;

use Magento\Checkout\Model\Session;
use ReflectionClass;

/**
 * An in-memory stand-in for the checkout session data bag, without booting a real session.
 */
class CheckoutSessionDataStub extends Session
{
    private array $sessionData = [];

    public static function create(): self
    {
        /** @var self $stub */
        $stub = (new ReflectionClass(self::class))->newInstanceWithoutConstructor();

        return $stub;
    }

    public function getData($key = '', $clear = false)
    {
        if ($key === '') {
            return $this->sessionData;
        }

        return $this->sessionData[$key] ?? null;
    }

    public function setData($key, $value = null)
    {
        $this->sessionData[$key] = $value;

        return $this;
    }
}
