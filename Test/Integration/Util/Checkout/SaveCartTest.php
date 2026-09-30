<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Checkout;

use LokiCheckout\Core\Util\Checkout\SaveCart;
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
