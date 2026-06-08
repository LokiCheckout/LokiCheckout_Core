<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Checkout;

use LokiCheckout\Core\Util\Checkout\SaveCart;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DataFixtureStorageManager;
use Magento\TestFramework\Fixture\DbIsolation;
use PHPUnit\Framework\TestCase;

#[
    DbIsolation(true),
    AppArea('frontend'),
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
]
class SaveCartTest extends TestCase
{
    private SaveCart $subject;
    private CartRepositoryInterface $cartRepository;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(SaveCart::class);
        $this->cartRepository = ObjectManager::getInstance()->get(CartRepositoryInterface::class);
    }

    public function testExecutePersistsBillingAddressChanges(): void
    {
        $quote = $this->getQuote();
        $quote->getBillingAddress()->setFirstname('SaveCartFirstname');

        $this->subject->execute($quote);

        $reloaded = $this->cartRepository->get((int)$quote->getId());
        $this->assertSame('SaveCartFirstname', $reloaded->getBillingAddress()->getFirstname());
    }

    public function testExecuteViaRepository(): void
    {
        $quote = $this->getQuote();
        $quote->getBillingAddress()->setLastname('RepoLastname');

        $this->subject->execute($quote, true);

        $reloaded = $this->cartRepository->get((int)$quote->getId());
        $this->assertSame('RepoLastname', $reloaded->getBillingAddress()->getLastname());
    }

    private function getQuote(): Quote
    {
        $storage = DataFixtureStorageManager::getStorage();
        $cartId = (int)$storage->get('cart')->getId();

        return $this->cartRepository->get($cartId);
    }
}
