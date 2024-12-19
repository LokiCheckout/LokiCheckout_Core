<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Newsletter\Model\GuestSubscriptionChecker;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Newsletter\Model\SubscriptionManagerInterface;
use Yireo\LokiComponents\Component\MutableComponentInterface;


class Newsletter implements MutableComponentInterface
{
    public bool $signup = false;

    public function __construct(
        private CheckoutSession $checkoutSession,
        private SubscriptionManagerInterface $subscriptionManager,
        private GuestSubscriptionChecker $guestSubscriptionChecker,
        private SubscriberFactory $subscriberFactory
    ) {
    }

    public function initialize(): void
    {
        $email = (string)$this->checkoutSession->getQuote()->getCustomerEmail();
        if (!empty($email)) {
            $this->signup= $this->guestSubscriptionChecker->isSubscribed($email);
        }
    }

    public function mutate(array $data = []): void
    {
        if (!isset($data['subscribe'])) {
            return;
        }

        $subscribe = (bool)$data['subscribe'];
        $email = (string)$this->checkoutSession->getQuote()->getCustomerEmail();
        $storeId = (int)$this->checkoutSession->getQuote()->getStoreId();
        if ($subscribe) {
            $this->subscriptionManager->subscribe($email, $storeId);
        } else {
            $subscriber = $this->subscriberFactory->create();
            $subscriber->load($email, 'email');
            if ($subscriber->getId() > 0) {
                $confirmCode = $subscriber->getSubscriberConfirmCode();
                $this->subscriptionManager->unsubscribe($email, $storeId, $confirmCode);
            }
        }
    }
}
