<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Payment\Vault;

use LokiCheckout\Core\Payment\Vault\PaymentTokenProvider;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\NotFoundException;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class PaymentTokenProviderTest extends TestCase
{
    private PaymentTokenProvider $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(PaymentTokenProvider::class);
    }

    public function testGetAllReturnsEmptyForGuest(): void
    {
        $this->assertSame([], $this->subject->getAll());
    }

    public function testGetAllNormalizedReturnsEmptyForGuest(): void
    {
        $this->assertSame([], $this->subject->getAllNormalized());
    }

    public function testGetByHashThrowsForGuest(): void
    {
        $this->expectException(NotFoundException::class);
        $this->subject->getByHash('any-hash');
    }
}
