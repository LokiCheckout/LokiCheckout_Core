<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration;

class LokiCheckoutPageTestCase extends LokiCheckoutTestCase
{
    protected ?bool $skipDispatchToCheckout = false;

    protected function setUp(): void
    {
        parent::setUp();

        if (true !== $this->skipDispatchToCheckout) {
            $this->dispatchToCheckout();
        }
    }
}
