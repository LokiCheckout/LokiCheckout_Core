<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util;

use LokiCheckout\Core\Test\Fixture\ShippingAddressFixture;
use LokiCheckout\Core\Util\AddressRenderer;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Fixture\DbIsolation;
use PHPUnit\Framework\TestCase;

/**
 * The cart does not need any products for these tests, so no product fixtures are used (saving time)
 */
#[
    DbIsolation(true),
    AppArea('frontend'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(ShippingAddressFixture::class, ['cart_id' => '$cart.id$'], 'shipping'),
]
class AddressRendererTest extends TestCase
{
    private AddressRenderer $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(AddressRenderer::class);
    }

    public function testGetHtmlForPopulatedAddress(): void
    {
        $quote = $this->getQuote();
        $html = $this->subject->getHtml($quote->getShippingAddress());

        $this->assertStringContainsString('Jane', $html);
        $this->assertStringContainsString('Doe', $html);
        $this->assertStringContainsString('Baarn', $html);
        $this->assertStringContainsString('Amalialaan 126', $html);
    }

    public function testGetHtmlForEmptyAddress(): void
    {
        $quote = $this->getQuote();
        $billingAddress = $quote->getBillingAddress();

        $this->assertSame('', $this->subject->getHtml($billingAddress));
    }

    private function getQuote(): Quote
    {
        $storage = DataFixtureStorageManager::getStorage();
        $cartId = (int)$storage->get('cart')->getId();

        $cartRepository = ObjectManager::getInstance()->get(CartRepositoryInterface::class);
        return $cartRepository->get($cartId);
    }
}
