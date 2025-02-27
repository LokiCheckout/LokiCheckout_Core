<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration;

class LokiCheckoutPageTestCase extends LokiCheckoutTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->dispatchToCheckout();
    }
}
