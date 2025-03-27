<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration;

class LokiCheckoutPageTestCase extends LokiCheckoutTestCase
{
    protected true|null $skipDispatchToCheckout = null;

    protected function setUp(): void
    {
        parent::setUp();

        if (true !== $this->skipDispatchToCheckout) {
            $this->dispatchToCheckout();
        }
    }
}
