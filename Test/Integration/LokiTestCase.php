<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Response\Http;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\TestFramework\Fixture\DataFixtureStorage;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\TestFramework\TestCase\AbstractController;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertModuleIsEnabled;
use Yireo\IntegrationTestHelper\Test\Integration\Traits\AssertStoreConfigValueEquals;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\LokiComponents\Filter\Filter;
use Yireo\LokiComponents\Test\Integration\Traits\AssertComponentHasError;
use Yireo\LokiComponents\Test\Integration\Traits\AssertComponentHasErrors;
use Yireo\LokiComponents\Test\Integration\Traits\AssertComponentHasNoErrors;
use Yireo\LokiComponents\Test\Integration\Traits\CreateComponent;
use Yireo\LokiComponents\Test\Integration\Traits\GetComponent;
use Yireo\LokiComponents\Util\IdConvertor;
use Yireo\LokiComponents\Validator\Validator;

class LokiTestCase extends AbstractController
{
    protected ?ObjectManagerInterface $objectManager = null;
    protected ?DataFixtureStorage $fixtures = null;

    use CartFixture;
    use AssertStoreConfigValueEquals;
    use AssertModuleIsEnabled;

    use GetComponent;
    use CreateComponent;
    use AssertComponentHasErrors;
    use AssertComponentHasError;
    use AssertComponentHasNoErrors;

    protected function setUp(): void
    {
        parent::setUp();

        $this->objectManager = Bootstrap::getObjectManager();
        $this->fixtures = DataFixtureStorageManager::getStorage();

        $this->assertModuleIsEnabled('Yireo_LokiComponents');
        $this->assertModuleIsEnabled('Yireo_LokiCheckout');
        $this->assertStoreConfigValueEquals(1, 'yireo_loki_checkout/general/enable');
    }

    public function dispatchToCheckout(CartInterface $cart)
    {
        $this->assertTrue($cart->getItemsCount() > 0);
        $this->getObjectManager()->get(LayoutInterface::class)->getUpdate()->addHandle([
            'checkout_index_index',
            'loki_checkout',
            'loki_checkout_theme_default',
        ]);

        $this->dispatch('checkout/index/index');

        /** @var Http $response */
        $response = $this->getResponse();
        $this->assertEquals(200, $response->getHttpResponseCode());
        $this->assertNotEmpty($response->getBody(), 'No body');
    }

    public function getComponentByBlockName(string $blockName): ComponentInterface
    {
        $layout = $this->getObjectManager()->get(LayoutInterface::class);
        $block = $layout->getBlock($blockName);
        $this->assertInstanceOf(Template::class, $block);

        $component = $block->getData('component');
        $this->assertInstanceOf(ComponentInterface::class, $component);

        return $component;
    }

    protected function getCart(): CartInterface
    {
        $cart = $this->fixtures->get('cart');

        $this->assertInstanceOf(CartInterface::class, $cart);

        $checkoutSession = $this->getObjectManager()->get(Session::class);
        $checkoutSession->setQuoteId($cart->getId());

        $this->assertSame($cart->getId(), $checkoutSession->getQuote()->getId());

        return $cart;
    }

    public function assertComponentExistsOnPage(string $blockName)
    {
        $body = $this->getResponse()->getBody();
        $elementId = $this->getObjectManager()->get(IdConvertor::class)->toElementId($blockName);
        $this->assertStringContainsString('"'.$elementId.'"', $body);
    }

    public function assertComponentNotExistsOnPage(string $blockName)
    {
        $body = $this->getResponse()->getBody();
        $this->assertStringNotContainsString('x-title="'.$blockName.'"', $body);
    }

    public function getValidator(): Validator
    {
        return $this->getObjectManager()->get(Validator::class);
    }

    public function getFilter(): Filter
    {
        return $this->getObjectManager()->get(Filter::class);
    }
}
