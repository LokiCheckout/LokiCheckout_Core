<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Magento\Newsletter\Model\GuestSubscriptionChecker;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldContext;
use Magento\Newsletter\Model\SubscriberFactory;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class NewsletterContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private readonly AddressFieldContext $parentContext,
        private readonly SubscriptionManagerInterface $subscriptionManager,
        private readonly GuestSubscriptionChecker $guestSubscriptionChecker,
        private readonly SubscriberFactory $subscriberFactory,
    ) {
    }

    public function getSubscriptionManager(): SubscriptionManagerInterface
    {
        return $this->subscriptionManager;
    }

    public function getGuestSubscriptionChecker(): GuestSubscriptionChecker
    {
        return $this->guestSubscriptionChecker;
    }

    public function getSubscriberFactory(): SubscriberFactory
    {
        return $this->subscriberFactory;
    }
}
