<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Other;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Newsletter\Model\GuestSubscriptionChecker;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Magewirephp\Magewire\Component;

class Newsletter extends Component
{
    public bool $signup = false;

    public function __construct(
        private CheckoutSession $checkoutSession,
        private SubscriptionManagerInterface $subscriptionManager,
        private GuestSubscriptionChecker $guestSubscriptionChecker,
        private SubscriberFactory $subscriberFactory
    ) {
    }

    public function boot(): void
    {
        $email = (string)$this->checkoutSession->getQuote()->getCustomerEmail();
        if (!empty($email)) {
            $this->signup= $this->guestSubscriptionChecker->isSubscribed($email);
        }
    }

    public function updatedSignup(bool $value): bool
    {
        $email = (string)$this->checkoutSession->getQuote()->getCustomerEmail();
        $storeId = (int)$this->checkoutSession->getQuote()->getStoreId();
        if ($value) {
            $this->subscriptionManager->subscribe($email, $storeId);
        } else {
            $subscriber = $this->subscriberFactory->create();
            $subscriber->load($email, 'email');
            if ($subscriber->getId() > 0) {
                $confirmCode = $subscriber->getSubscriberConfirmCode();
                $this->subscriptionManager->unsubscribe($email, $storeId, $confirmCode);
            }
        }

        return $value;
    }
}
