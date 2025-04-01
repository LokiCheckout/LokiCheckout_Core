<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration;

use Yireo\LokiCheckout\Test\Integration\Trait\EnableCartRepositoryStub;

class LokiCheckoutRepositoryTestCase extends LokiCheckoutTestCase
{
    use EnableCartRepositoryStub;

    protected function setUp(): void
    {
        $this->enableCartRepositoryStub();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->disableCartRepositoryStub();
        parent::tearDown();
    }
}
