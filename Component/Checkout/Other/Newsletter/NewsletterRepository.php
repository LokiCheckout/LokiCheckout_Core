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
        $email = (string)$this->getContext()->getCheckoutSession()->getQuote()->getCustomerEmail();
        if (!empty($email)) {
            return $this->getContext()->getGuestSubscriptionChecker()->isSubscribed($email);
        }

        return false;
    }

    protected function saveData(mixed $data): void
    {
        $subscribe = (bool)$data;
        $email = (string)$this->getContext()->getCheckoutSession()->getQuote()->getCustomerEmail();
        $storeId = (int)$this->getContext()->getCheckoutSession()->getQuote()->getStoreId();
        if (true === $subscribe) {
            $this->getContext()->getSubscriptionManager()->subscribe($email, $storeId);

            return;
        }

        $subscriber = $this->getContext()->getSubscriberFactory()->create();
        $subscriber->load($email, 'subscriber_email');
        echo 'Subscriber: ' . $subscriber->getId();
        if ($subscriber->getId() > 0) {
            $confirmCode = $subscriber->getSubscriberConfirmCode();
            $this->getContext()->getSubscriptionManager()->unsubscribe($email, $storeId, $confirmCode);
        }
    }
}
