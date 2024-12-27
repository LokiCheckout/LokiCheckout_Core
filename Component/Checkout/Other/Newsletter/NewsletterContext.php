<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Newsletter\Model\GuestSubscriptionChecker;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Yireo\LokiCheckout\Util\Component\StepProvider;
use Yireo\LokiComponents\Component\ComponentContextInterface;
use Magento\Newsletter\Model\SubscriberFactory;

class NewsletterContext implements ComponentContextInterface
{
    public function __construct(
        public CheckoutSession $checkoutSession,
        public readonly StepProvider $stepProvider,
        public SubscriptionManagerInterface $subscriptionManager,
        public GuestSubscriptionChecker $guestSubscriptionChecker,
        public SubscriberFactory $subscriberFactory
    ) {
    }
}
