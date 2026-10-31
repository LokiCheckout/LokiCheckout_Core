<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Payment\Redirect;

use LokiCheckout\Core\Payment\Redirect\SessionRedirectResolver;
use LokiCheckout\Core\Step\FinalStep\RedirectContext;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\UrlInterface;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class SessionRedirectResolverTest extends TestCase
{
    private CheckoutState $checkoutState;
    private SessionRedirectResolver $subject;

    protected function setUp(): void
    {
        $this->checkoutState = ObjectManager::getInstance()->get(CheckoutState::class);
        $this->subject = ObjectManager::getInstance()->get(SessionRedirectResolver::class);
    }

    protected function tearDown(): void
    {
        $this->checkoutState->getCheckoutSession()->setRedirectUrl(null);
    }

    public function testReturnsRedirectUrlWhenSet(): void
    {
        $this->checkoutState->getCheckoutSession()->setRedirectUrl('https://example.com/pay');

        $result = $this->subject->resolve($this->getContext());
        $this->assertSame('https://example.com/pay', $result);
    }

    public function testTurnsRootRelativePathIntoStoreUrl(): void
    {
        $this->checkoutState->getCheckoutSession()->setRedirectUrl('/cvb/order/redirect?foo=bar');

        $result = $this->subject->resolve($this->getContext());
        $expectedUrl = ObjectManager::getInstance()->get(UrlInterface::class)
            ->getDirectUrl('cvb/order/redirect?foo=bar');

        $this->assertSame($expectedUrl, $result);
        $this->assertMatchesRegularExpression('#^https?://[^/]+/(index\.php/)?cvb/order/redirect\?foo=bar$#', (string)$result);
    }

    public function testKeepsProtocolRelativeUrl(): void
    {
        $this->checkoutState->getCheckoutSession()->setRedirectUrl('//example.com/pay');

        $this->assertSame('//example.com/pay', $this->subject->resolve($this->getContext()));
    }

    public function testReturnsFalseWhenEmpty(): void
    {
        $this->checkoutState->getCheckoutSession()->setRedirectUrl('');

        $this->assertFalse($this->subject->resolve($this->getContext()));
    }

    public function testReturnsFalseWhenZeroString(): void
    {
        $this->checkoutState->getCheckoutSession()->setRedirectUrl('0');

        $this->assertFalse($this->subject->resolve($this->getContext()));
    }

    private function getContext(): RedirectContext
    {
        return ObjectManager::getInstance()->get(RedirectContext::class);
    }
}
