<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Composer;

use LokiCheckout\Core\Util\Composer\ComposerLock;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;

class ComposerLockTest extends TestCase
{
    private ComposerLock $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(ComposerLock::class);
    }

    public function testGetComposerLockDataContainsPackages(): void
    {
        $data = $this->subject->getComposerLockData();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('packages', $data);
    }

    public function testGetComposerVersionForKnownPackage(): void
    {
        $version = $this->subject->getComposerVersion('loki-checkout/magento2-core');

        $this->assertNotSame('', $version);
        $this->assertNotSame('unknown', $version);
    }

    public function testGetComposerVersionForUnknownPackage(): void
    {
        $version = $this->subject->getComposerVersion('loki-checkout/this-does-not-exist');

        $this->assertSame('unknown', $version);
    }

    public function testGetComposerDateReturnsFormattedDate(): void
    {
        $date = $this->subject->getComposerDate('loki-checkout/magento2-core');

        $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $date);
    }
}
