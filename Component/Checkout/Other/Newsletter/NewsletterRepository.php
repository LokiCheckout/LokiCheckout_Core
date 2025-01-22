<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method NewsletterContext getContext()
 */
class NewsletterRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        $email = (string)$this->getContext()->getCheckoutState()->getQuote()->getCustomerEmail();
        if (!empty($email)) {
            return $this->getContext()->getGuestSubscriptionChecker()->isSubscribed($email);
        }

        return false;
    }

    public function saveValue(mixed $value): void
    {
        $subscribe = (bool)$value;
        $email = (string)$this->getContext()->getCheckoutState()->getQuote()->getCustomerEmail();
        $storeId = (int)$this->getContext()->getCheckoutState()->getQuote()->getStoreId();
        if (true === $subscribe) {
            $this->getContext()->getSubscriptionManager()->subscribe($email, $storeId);

            return;
        }

        $subscriber = $this->getContext()->getSubscriberFactory()->create();
        $subscriber->load($email, 'subscriber_email');
        if ($subscriber->getId() > 0) {
            $confirmCode = $subscriber->getSubscriberConfirmCode();
            $this->getContext()->getSubscriptionManager()->unsubscribe($email, $storeId, $confirmCode);
        }
    }
}
