<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Magento\Newsletter\Model\GuestSubscriptionChecker;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Magento\Newsletter\Model\SubscriberFactory;

class NewsletterContext extends GenericContext
{
    public function getSubscriptionManager(): SubscriptionManagerInterface
    {
        return $this->get(SubscriptionManagerInterface::class);
    }

    public function getGuestSubscriptionChecker(): GuestSubscriptionChecker
    {
        return $this->get(GuestSubscriptionChecker::class);
    }

    public function getSubscriberFactory(): SubscriberFactory
    {
        return $this->get(SubscriberFactory::class);
    }
}
