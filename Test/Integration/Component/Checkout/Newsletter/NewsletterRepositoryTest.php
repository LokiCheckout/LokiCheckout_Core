<?php

declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Component\Checkout\Newsletter;

use LokiCheckout\Core\Repository\NewsletterRepository as SubscriberRepository;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Loki\Components\Component\ComponentRepositoryInterface;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;

#[DbIsolation(true)]
#[DataFixture(ProductFixture::class, as: 'product')]
#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$'])]
#[AppArea('frontend')]
final class NewsletterRepositoryTest extends LokiCheckoutTestCase
{
    private const BLOCK_NAME = 'loki-checkout.newsletter';
    private const EMAIL = 'newsletter-guest@example.com';

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 1, 'store', 'default')]
    public function testGuestIsSubscribedAutomaticallyWhenCheckedByDefault(): void
    {
        $repository = $this->getNewsletterRepository();

        $this->assertTrue($repository->getValue());
        $this->assertTrue($this->getSubscriberRepository()->isGuestEmailSubscribed(self::EMAIL));
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 1, 'store', 'default')]
    public function testUncheckingAfterAutomaticSubscriptionUnsubscribesGuest(): void
    {
        $repository = $this->getNewsletterRepository();
        $this->assertTrue($repository->getValue());

        $repository->saveValue(false);

        $this->assertFalse($this->getSubscriberRepository()->isGuestEmailSubscribed(self::EMAIL));
        $this->assertFalse($repository->getValue());
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 1, 'store', 'default')]
    public function testUncheckingWithoutSubscriberRecordSavesOptOut(): void
    {
        $repository = $this->getNewsletterRepository();
        $this->assertFalse($this->getSubscriberRepository()->hasGuestEmailRecord(self::EMAIL));

        $repository->saveValue(false);

        $this->assertTrue($this->getSubscriberRepository()->hasGuestEmailRecord(self::EMAIL));
        $this->assertFalse($this->getSubscriberRepository()->isGuestEmailSubscribed(self::EMAIL));
        $this->assertFalse($repository->getValue());
        $this->assertFalse($this->getSubscriberRepository()->isGuestEmailSubscribed(self::EMAIL));
    }

    #[ConfigFixture('newsletter/general/active', 1, 'store', 'default')]
    #[ConfigFixture('newsletter/subscription/confirm', 0, 'store', 'default')]
    #[ConfigFixture('loki_checkout/newsletter/default_value', 0, 'store', 'default')]
    public function testUncheckingWithoutSubscriberRecordDoesNothingWhenNotCheckedByDefault(): void
    {
        $repository = $this->getNewsletterRepository();

        $repository->saveValue(false);

        $this->assertFalse($this->getSubscriberRepository()->hasGuestEmailRecord(self::EMAIL));
        $this->assertFalse($repository->getValue());
    }

    private function getNewsletterRepository(): ComponentRepositoryInterface
    {
        $this->getCart();
        $this->getQuote()->setCustomerEmail(self::EMAIL);

        $repository = $this->getCheckoutComponent(self::BLOCK_NAME)->getRepository();
        $this->assertInstanceOf(ComponentRepositoryInterface::class, $repository);

        return $repository;
    }

    private function getSubscriberRepository(): SubscriberRepository
    {
        return $this->getObjectManager()->get(SubscriberRepository::class);
    }
}
