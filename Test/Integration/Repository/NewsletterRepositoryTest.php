<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Repository;

use LokiCheckout\Core\Repository\NewsletterRepository;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use PHPUnit\Framework\TestCase;
use LokiCheckout\Core\Test\Fixture\CustomerFixture;

#[
    ConfigFixture('loki_checkout/newsletter/default_value', 0, 'store', 'default'),
    DataFixture(CustomerFixture::class, ['email' => 'info@example.com'], as: 'customer'),
    AppArea('frontend')
]
class NewsletterRepositoryTest extends TestCase
{
    public function testsWithCustomerId()
    {
        $customerRepository = ObjectManager::getInstance()->get(CustomerRepositoryInterface::class);
        $customer = $customerRepository->get('info@example.com');
        $customerId = (int)$customer->getId();

        /** @var NewsletterRepository $newsletterRepository */
        $newsletterRepository = ObjectManager::getInstance()->get(NewsletterRepository::class);
        $this->assertFalse($newsletterRepository->hasCustomerIdRecord($customerId));
        $this->assertFalse($newsletterRepository->isCustomerIdSubscribed($customerId));

        $subscriptionManager = ObjectManager::getInstance()->get(SubscriptionManagerInterface::class);
        $subscriptionManager->subscribeCustomer($customerId, 1);
        $this->assertTrue($newsletterRepository->hasCustomerIdRecord($customerId));
        $this->assertTrue($newsletterRepository->isCustomerIdSubscribed($customerId));

        $subscriptionManager->unsubscribeCustomer($customerId, 1);
        $this->assertTrue($newsletterRepository->hasCustomerIdRecord($customerId));
        $this->assertFalse($newsletterRepository->isCustomerIdSubscribed($customerId));
    }

    public function testsWithGuestEmail()
    {
        $email = 'info@example.com';

        $newsletterRepository = ObjectManager::getInstance()->get(NewsletterRepository::class);
        $this->assertFalse($newsletterRepository->hasGuestEmailRecord($email));

        $subscriptionManager = ObjectManager::getInstance()->get(SubscriptionManagerInterface::class);
        $subscriptionManager->subscribe($email, 1);
        $this->assertTrue($newsletterRepository->hasGuestEmailRecord($email));

        $confirmationCode = $newsletterRepository->getConfirmationCode($email);
        $subscriptionManager->unsubscribe($email, 1, $confirmationCode);

        $this->assertTrue($newsletterRepository->hasGuestEmailRecord($email));
        $this->assertFalse($newsletterRepository->isGuestEmailSubscribed($email));
    }
}
