<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method NewsletterContext getContext()
 */
class NewsletterRepository extends ComponentRepository
{
    protected function getData(): mixed
    {
        $email = (string)$this->getContext()->checkoutSession->getQuote()->getCustomerEmail();
        if (!empty($email)) {
            return $this->getContext()->guestSubscriptionChecker->isSubscribed($email);
        }

        return false;
    }

    protected function saveData(mixed $data): void
    {
        if (!isset($data['subscribe'])) {
            return;
        }

        $subscribe = (bool)$data['subscribe'];
        $email = (string)$this->getContext()->checkoutSession->getQuote()->getCustomerEmail();
        $storeId = (int)$this->getContext()->checkoutSession->getQuote()->getStoreId();
        if ($subscribe) {
            $this->getContext()->subscriptionManager->subscribe($email, $storeId);
        } else {
            $subscriber = $this->getContext()->subscriberFactory->create();
            $subscriber->load($email, 'email');
            if ($subscriber->getId() > 0) {
                $confirmCode = $subscriber->getSubscriberConfirmCode();
                $this->getContext()->subscriptionManager->unsubscribe($email, $storeId, $confirmCode);
            }
        }
    }
}
