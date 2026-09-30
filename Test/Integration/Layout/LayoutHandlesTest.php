<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Layout;

use Loki\Components\Layout\LayoutHandlerComposite;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Loki\Components\Util\Controller\LayoutLoader;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Helper\Bootstrap;
use PHPUnit\Framework\TestCase;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsEnabled;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsRegistered;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsRegisteredForReal;
/**
 * The cart does not need any products for these tests, so no product fixtures are used (saving time)
 */
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[AppArea('frontend')]
class LayoutHandlesTest extends AbstractLayoutTestCase
{
    public function testForLayoutHandles(): void
    {
        $expectedHandles = [
            'checkout_index_index',
            'luma_checkout_index_index',
            'loki_checkout',
            'loki_checkout_skin_default',
            'loki_checkout_address_default_grid',
            'loki_checkout_country_us',
            'loki_checkout_skin_default_country_us',
            'loki_checkout_address_default_grid_country_us',
        ];

        $layoutHandlerComposite = ObjectManager::getInstance()->get(LayoutHandlerComposite::class);
        $actualHandles = $layoutHandlerComposite->getHandles(['checkout_index_index']);

        foreach ($expectedHandles as $expectedHandle) {
            $this->assertContains($expectedHandle, $actualHandles);
        }
    }
}
