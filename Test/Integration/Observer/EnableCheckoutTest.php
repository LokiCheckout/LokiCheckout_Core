<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Observer;

use LokiCheckout\Core\Observer\EnableCheckout;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class EnableCheckoutTest extends TestCase
{
    #[ConfigFixture('loki_checkout/general/enable', 0, 'store', 'default')]
    public function testNoOpWhenAccessNotAllowed(): void
    {
        $layout = ObjectManager::getInstance()->create(LayoutInterface::class);
        $layout->getUpdate()->addHandle('checkout_index_index');

        $observer = new Observer(['layout' => $layout]);

        $subject = ObjectManager::getInstance()->get(EnableCheckout::class);
        $subject->execute($observer);

        $this->assertTrue(true);
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testNoOpWhenCheckoutHandleAbsent(): void
    {
        $layout = ObjectManager::getInstance()->create(LayoutInterface::class);
        $layout->getUpdate()->addHandle('cms_index_index');

        $observer = new Observer(['layout' => $layout]);

        $subject = ObjectManager::getInstance()->get(EnableCheckout::class);
        $subject->execute($observer);

        $this->assertNotContains('loki_checkout', $layout->getUpdate()->getHandles());
    }
}
