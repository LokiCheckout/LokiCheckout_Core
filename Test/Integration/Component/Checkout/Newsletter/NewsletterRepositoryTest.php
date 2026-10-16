<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Newsletter;

use LokiCheckout\Core\Repository\NewsletterRepository as SubscriberRepository;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use LokiCheckout\Core\Util\NewsletterChoice;
use Loki\Components\Component\ComponentRepositoryInterface;
use Magento\Framework\Message\ManagerInterface as MessageManagerInterface;
use Magento\Framework\Message\MessageInterface;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;

/**
 * The cart does not need any products for these tests, so no product fixtures are used (saving time)
 */
#[DbIsolation(true)]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[AppArea('frontend')]
final class NewsletterRepositoryTest extends LokiCheckoutTestCase
{
    private const BLOCK_NAME = 'loki-checkout.newsletter';
    private const EMAIL = 'newsletter-guest@example.com';

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 1, 'store', 'default')]
    public function testGuestIsNotSubscribedWhenRenderingButBoxIsCheckedByDefault(): void
    {
        $repository = $this->getNewsletterRepository();

        $this->assertTrue($repository->getValue());
        $this->assertFalse($this->getSubscriberRepository()->hasGuestEmailRecord(self::EMAIL));
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 0, 'store', 'default')]
    public function testGuestCanUndoOwnSubscription(): void
    {
        $repository = $this->getNewsletterRepository();
        $this->assertFalse($repository->getValue());

        $repository->saveValue(true);
        $this->assertTrue($this->getSubscriberRepository()->isGuestEmailSubscribed(self::EMAIL));
        $this->assertTrue($repository->getValue());

        $repository->saveValue(false);
        $this->assertFalse($this->getSubscriberRepository()->isGuestEmailSubscribed(self::EMAIL));
        $this->assertFalse($repository->getValue());
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 0, 'store', 'default')]
    public function testGuestCanNotUnsubscribeForeignEmail(): void
    {
        $this->getSubscriptionManager()->subscribe(self::EMAIL, 1);
        $this->assertTrue($this->getSubscriberRepository()->isGuestEmailSubscribed(self::EMAIL));

        $repository = $this->getNewsletterRepository();
        $repository->saveValue(true);
        $repository->saveValue(false);

        $this->assertTrue($this->getSubscriberRepository()->isGuestEmailSubscribed(self::EMAIL));
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 0, 'store', 'default')]
    public function testSubscriptionStateOfForeignEmailIsNotDisclosed(): void
    {
        $this->getSubscriptionManager()->subscribe(self::EMAIL, 1);

        $repository = $this->getNewsletterRepository();
        $this->assertFalse($repository->getValue());
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 1, 'store', 'default')]
    public function testUncheckingByDefaultCheckedBoxDoesNotCreateRecord(): void
    {
        $repository = $this->getNewsletterRepository();

        $repository->saveValue(false);

        $this->assertFalse($this->getSubscriberRepository()->hasGuestEmailRecord(self::EMAIL));
        $this->assertFalse($repository->getValue());
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 0, 'store', 'default')]
    #[ConfigFixture('recaptcha_frontend/type_for/newsletter', 'recaptcha', 'store', 'default')]
    #[ConfigFixture('recaptcha_frontend/type_recaptcha/public_key', 'site-key', 'website', 'base')]
    #[ConfigFixture('recaptcha_frontend/type_recaptcha/private_key', 'secret-key', 'website', 'base')]
    public function testChoiceIsNotRememberedWhenRecaptchaFails(): void
    {
        $repository = $this->getNewsletterRepository();

        $repository->saveValue(true);

        $this->assertFalse($this->getSubscriberRepository()->hasGuestEmailRecord(self::EMAIL));
        $this->assertNull($this->getObjectManager()->get(NewsletterChoice::class)->getChoice(self::EMAIL));
        $this->assertFalse($repository->getValue());
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 0, 'store', 'default')]
    public function testNotificationsCanBeDisabled(): void
    {
        $messageManager = $this->getObjectManager()->get(MessageManagerInterface::class);
        $messageManager->getMessages(true);

        $repository = $this->getNewsletterRepository();
        $this->getCheckoutComponent(self::BLOCK_NAME)->getBlock()->setDisableNotifications(true);

        $repository->saveValue(true);

        $this->assertTrue($this->getSubscriberRepository()->isGuestEmailSubscribed(self::EMAIL));
        $this->assertSame(0, $messageManager->getMessages(true)->getCountByType(MessageInterface::TYPE_NOTICE));
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 0, 'store', 'default')]
    public function testNotificationsAreShownByDefault(): void
    {
        $messageManager = $this->getObjectManager()->get(MessageManagerInterface::class);
        $messageManager->getMessages(true);

        $repository = $this->getNewsletterRepository();
        $repository->saveValue(true);

        $this->assertSame(1, $messageManager->getMessages(true)->getCountByType(MessageInterface::TYPE_NOTICE));
    }

    private function getNewsletterRepository(): ComponentRepositoryInterface
    {
        $this->getObjectManager()->get(NewsletterChoice::class)->reset();
        $this->getCart();
        $this->getQuote()->setCustomerEmail(self::EMAIL);

        $repository = $this->getCheckoutComponent(self::BLOCK_NAME)->getRepository();
        $this->assertInstanceOf(ComponentRepositoryInterface::class, $repository);

        return $repository;
    }

    private function getSubscriptionManager(): SubscriptionManagerInterface
    {
        return $this->getObjectManager()->get(SubscriptionManagerInterface::class);
    }

    private function getSubscriberRepository(): SubscriberRepository
    {
        return $this->getObjectManager()->get(SubscriberRepository::class);
    }
}
