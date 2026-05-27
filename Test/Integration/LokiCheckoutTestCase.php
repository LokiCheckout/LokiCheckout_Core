<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\Http;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Loki\Components\Component\ComponentInterface;
use Loki\Components\Component\ComponentRegistry;
use Loki\Components\Component\ComponentRepositoryInterface;
use Loki\Components\Test\Integration\LokiComponentsTestCase;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;

class LokiCheckoutTestCase extends LokiComponentsTestCase
{
    protected ?string $stepCode = null;

    protected function assertLokiCheckoutIsEnabled(): void
    {
        $this->assertModuleIsEnabled('LokiCheckout_Core');
        $this->assertModuleIsEnabled('Loki_FieldComponents');
        $this->assertStoreConfigValueEquals(1, 'loki_checkout/general/enable');
    }

    protected function getQuote(): Quote
    {
        return $this->getObjectManager()->get(CheckoutState::class)->getQuote();
    }

    protected function getComponentRepository(string $blockId): ComponentRepositoryInterface
    {
        $componentRegistry = $this->getObjectManager()->get(ComponentRegistry::class);
        $component = $componentRegistry->getComponentByName($blockId);

        return $component->getRepository();
    }

    protected function getCheckoutComponent(string $blockName): ComponentInterface
    {
        $config = $this->getObjectManager()->get(Config::class);
        $skinName = $config->getSkin();

        $handles = [
            'default',
            'loki_components',
            'loki_field_components',
            'loki_checkout',
            'loki_checkout_skin_' . $skinName,
        ];

        return $this->getComponentWithBlock($blockName, $handles);
    }

    protected function dispatchToCheckout(?Quote $cart = null)
    {
        $this->assertLokiComponentsIsEnabled();
        $this->assertLokiCheckoutIsEnabled();

        if (false === $cart instanceof Quote) {
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

    protected function getCart(): Quote
    {
        /** @var Quote $cart */
        $cart = $this->fixtures->get('cart');
        $this->assertInstanceOf(Quote::class, $cart, 'Cart fixture is empty');

        $cartId = $cart->getId();
        $cart = $this->getObjectManager()->get(CartRepositoryInterface::class)->get($cartId);

        $this->assertNotEmpty($cart->getId());

        $checkoutState = $this->getObjectManager()->get(CheckoutState::class);
        $checkoutState->setQuote($cart);
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
        $scopeConfig = $this->getObjectManager()->get(ScopeConfigInterface::class);
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
        $scopeConfig = $this->getObjectManager()->get(ScopeConfigInterface::class);
        $value = $scopeConfig->isSetFlag($path, $scopeType, $scopeValue);
        $this->assertSame($expectedValue, $value);
    }

    protected function assertCustomerLoggedIn(string $email = 'info@example.com'): void
    {
        $customerSession = $this->getObjectManager()->get(Session::class);
        $this->assertTrue($customerSession->isLoggedIn());
        $this->assertSame($email, $customerSession->getCustomer()->getEmail());
    }

    protected function assertCustomerHasAddresses(): void
    {
        $customerSession = $this->getObjectManager()->get(Session::class);
        $customer = $customerSession->getCustomer();
        $addresses = $customer->getAddresses();
        $this->assertNotEmpty($addresses);
    }

    protected function assertCustomerHasNoAddresses(): void
    {
        $customerSession = $this->getObjectManager()->get(Session::class);
        $customer = $customerSession->getCustomer();
        $addresses = $customer->getAddresses();
        $this->assertEmpty($addresses);
    }
}
