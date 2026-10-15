<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Step\FinalStep\Task;

use LokiCheckout\Core\Repository\NewsletterRepository;
use LokiCheckout\Core\Step\FinalStep\Task\NewsletterTask;
use LokiCheckout\Core\Util\NewsletterChoice;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteFactory;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DbIsolation;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
#[DbIsolation(true)]
class NewsletterTaskTest extends TestCase
{
    private const EMAIL = 'newsletter-task@example.com';

    protected function setUp(): void
    {
        ObjectManager::getInstance()->get(NewsletterChoice::class)->reset();
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 1, 'store', 'default')]
    public function testGuestIsSubscribedAfterPlacingOrderWhenCheckedByDefault(): void
    {
        $this->runTask();

        $this->assertTrue($this->getSubscriberRepository()->isGuestEmailSubscribed(self::EMAIL));
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 1, 'store', 'default')]
    public function testGuestIsNotSubscribedAfterUnchecking(): void
    {
        ObjectManager::getInstance()->get(NewsletterChoice::class)->setChoice(false);

        $this->runTask();

        $this->assertFalse($this->getSubscriberRepository()->hasGuestEmailRecord(self::EMAIL));
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 0, 'store', 'default')]
    public function testGuestIsNotSubscribedWhenNotCheckedByDefault(): void
    {
        $this->runTask();

        $this->assertFalse($this->getSubscriberRepository()->hasGuestEmailRecord(self::EMAIL));
    }

    private function runTask(): void
    {
        /** @var Quote $quote */
        $quote = ObjectManager::getInstance()->get(QuoteFactory::class)->create();
        $quote->setCustomerEmail(self::EMAIL);
        $quote->setStoreId(1);

        $task = ObjectManager::getInstance()->create(NewsletterTask::class);
        $task->beforePlaceOrder($quote);
        $task->afterPlaceOrder(1);
    }

    private function getSubscriberRepository(): NewsletterRepository
    {
        return ObjectManager::getInstance()->get(NewsletterRepository::class);
    }
}
