<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Unit\Stub;

use Magento\Checkout\Model\Session;

class CheckoutSessionStub extends Session
{
    public function getCurrentGridLayout(): string
    {
        return '';
    }

    public function setCurrentGridLayout(string $currentGridLayout): void
    {
    }
}
