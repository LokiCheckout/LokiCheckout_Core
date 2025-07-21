<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration;

use LokiCheckout\Core\Test\Integration\Trait\EnableCartRepositoryStub;

// WARNING: As yet, this test case is not working, thus marked as "final" to prevent usage
final class LokiCheckoutRepositoryTestCase extends LokiCheckoutTestCase
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
