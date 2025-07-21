<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Response\Http;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Loki\Components\Component\ComponentInterface;
use Loki\Components\Component\ComponentRegistry;
use Loki\Components\Component\ComponentRepositoryInterface;
use Loki\Components\Test\Integration\LokiComponentsTestCase;

class LokiCheckoutTestCase extends LokiComponentsTestCase
{
    protected ?string $stepCode = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assertModuleIsEnabled('LokiCheckout_Core');
        $this->assertModuleIsEnabled('Loki_FieldComponents');
        $this->assertStoreConfigValueEquals(1, 'loki_checkout/general/enable');
    }

    protected function getQuote(): Quote
    {
        return ObjectManager::getInstance()->get(CheckoutState::class)->getQuote();
    }

    protected function getComponentRepository(string $blockId): ComponentRepositoryInterface
    {
        $componentRegistry = ObjectManager::getInstance()->get(ComponentRegistry::class);
        $component = $componentRegistry->getComponentByName($blockId);

        return $component->getRepository();
    }

    protected function getCheckoutComponent(string $blockName): ComponentInterface
    {
        $config = ObjectManager::getInstance()->get(Config::class);
        $themeName = $config->getTheme();

        $handles = [
            'default',
            'loki_components',
            'loki_field_components',
            'loki_checkout',
            'loki_checkout_theme_' . $themeName,
        ];

        return $this->getComponentWithBlock($blockName, $handles);
    }

    protected function dispatchToCheckout(?CartInterface $cart = null)
    {
        if (false === $cart instanceof CartInterface) {
            $cart = $this->getCart();
        }

        $this->assertTrue($cart->getItemsCount() > 0);

        $url = 'checkout/index/index';

        if ($this->stepCode !== '' && $this->stepCode !== '0') {
            $this->getRequest()->setParams(['step' => $this->stepCode]);
        }

        $this->dispatch($url);

        /** @var Http $response */
        $response = $this->getResponse();
        $this->assertEquals(200, $response->getHttpResponseCode());
        $this->assertNotEmpty($response->getBody(), 'No body');
    }

    protected function getCart(): CartInterface
    {
        /** @var CartInterface $cart */
        $cart = $this->fixtures->get('cart');
        $this->assertInstanceOf(CartInterface::class, $cart);

        $cartId = $cart->getId();
        $cart = $this->getObjectManager()->get(CartRepositoryInterface::class)->get($cartId);

        $this->assertNotEmpty($cart->getId());

        $checkoutState = $this->getObjectManager()->get(CheckoutState::class);
        $checkoutState->setQuoteId((int)$cart->getId());

        $checkoutSession = $this->getObjectManager()->get(CheckoutSession::class);
        $checkoutSession->replaceQuote($cart);

        return $cart;
    }

    // @todo: Move into trait
    protected function assertRepositorySave(ComponentInterface $component, mixed $value)
    {
        $repository = $component->getRepository();
        $this->assertInstanceOf(ComponentRepositoryInterface::class, $repository);

        $repository->saveValue($value);
        $this->assertSame($value, $repository->getValue());
    }

    // @todo: Move into trait
    protected function assertConfigValue(
        mixed $expectedValue,
        string $path,
        string $scopeType = 'default',
        mixed $scopeValue = null
    ): void {
        $scopeConfig = ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        $value = $scopeConfig->getValue($path, $scopeType, $scopeValue);
        $this->assertSame($expectedValue, $value);
    }

    // @todo: Move into trait
    protected function assertConfigFlag(
        bool $expectedValue,
        string $path,
        string $scopeType = 'default',
        mixed $scopeValue = null
    ): void {
        $scopeConfig = ObjectManager::getInstance()->get(ScopeConfigInterface::class);
        $value = $scopeConfig->isSetFlag($path, $scopeType, $scopeValue);
        $this->assertSame($expectedValue, $value);
    }
}
