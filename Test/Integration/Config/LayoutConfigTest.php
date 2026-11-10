<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Config;

use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Config\LayoutConfig;
use LokiCheckout\Core\Test\Integration\Layout\AbstractLayoutTestCase;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Helper\Bootstrap;

#[AppArea('frontend')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
class LayoutConfigTest extends AbstractLayoutTestCase
{
    #[ConfigFixture('loki_checkout/fields/enable_same_as_billing', 1, 'store', 'default')]
    #[ConfigFixture('loki_checkout/cart/max_items_visible_count', 7, 'store', 'default')]
    public function testLayoutOverridesScopeConfig(): void
    {
        $layout = $this->getLayout();
        $block = $layout->getBlock(LayoutConfig::BLOCK_NAME);
        $this->assertInstanceOf(AbstractBlock::class, $block);

        $om = Bootstrap::getObjectManager();
        $layoutConfig = $om->get(LayoutConfig::class);
        $config = $om->get(Config::class);

        $layoutConfig->loadFromLayout($layout);
        $this->assertTrue($config->enableSameAsBilling());
        $this->assertSame(7, $config->getMaxCartItemsToShowByDefault());

        $block->setData('config', [
            'loki_checkout/fields/enable_same_as_billing' => false,
            'loki_checkout/cart/max_items_visible_count' => 5,
        ]);
        $layoutConfig->loadFromLayout($layout);

        $this->assertFalse($config->enableSameAsBilling());
        $this->assertSame(5, $config->getMaxCartItemsToShowByDefault());

        $block->setData('config', []);
        $layoutConfig->loadFromLayout($layout);
    }
}
