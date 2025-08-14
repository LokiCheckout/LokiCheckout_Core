<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Repository;

use LokiCheckout\Core\Repository\NewsletterRepository;
use Magento\Framework\App\ObjectManager;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use PHPUnit\Framework\TestCase;
use Magento\Customer\Test\Fixture\Customer as CustomerFixture;

#[DataFixture(CustomerFixture::class, ['email' => 'info@example.com'], as: 'customer')]
#[AppArea('frontend')]
class NewsletterRepositoryTest extends TestCase
{
    public function testsWithCustomerId()
    {
        $newsletterRepository = ObjectManager::getInstance()->get(NewsletterRepository::class);
        $this->assertFalse($newsletterRepository->hasCustomerIdRecord(1));
        $this->assertFalse($newsletterRepository->isCustomerIdSubscribed(1));

        $subscriptionManager = ObjectManager::getInstance()->get(SubscriptionManagerInterface::class);
        $subscriptionManager->subscribeCustomer(1, 1);
        $this->assertTrue($newsletterRepository->hasCustomerIdRecord(1));
        $this->assertTrue($newsletterRepository->isCustomerIdSubscribed(1));

        $subscriptionManager->unsubscribeCustomer(1, 1);
        $this->assertTrue($newsletterRepository->hasCustomerIdRecord(1));
        $this->assertFalse($newsletterRepository->isCustomerIdSubscribed(1));
    }

    public function testsWithGuestEmail()
    {
        $email = 'info@example.com';

        $newsletterRepository = ObjectManager::getInstance()->get(NewsletterRepository::class);
        $this->assertFalse($newsletterRepository->hasGuestEmailRecord($email));

        $subscriptionManager = ObjectManager::getInstance()->get(SubscriptionManagerInterface::class);
        $subscriptionManager->subscribe($email, 1);
        $this->assertTrue($newsletterRepository->hasGuestEmailRecord($email));

        $subscriptionManager->unsubscribe($email, 1, '');
        $this->assertTrue($newsletterRepository->hasGuestEmailRecord($email));
        $this->assertFalse($newsletterRepository->isGuestEmailSubscribed($email));
    }
}
