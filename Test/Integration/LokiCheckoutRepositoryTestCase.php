<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration;

use Yireo\LokiCheckout\Test\Integration\Trait\EnableCartRepositoryFixture;

class LokiCheckoutRepositoryTestCase extends LokiCheckoutTestCase
{
    use EnableCartRepositoryFixture;

    protected function setUp(): void
    {
        $this->enableCartRepositoryFixture();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        $this->disableCartRepositoryFixture();
        parent::tearDown();
    }
}
