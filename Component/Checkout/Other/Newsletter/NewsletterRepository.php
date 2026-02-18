<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Newsletter;

use Magento\Newsletter\Model\Subscriber;
use Loki\Field\Component\Base\Field\FieldRepository;

/**
 * @method NewsletterContext getContext()
 */
class NewsletterRepository extends FieldRepository
{
    public function getValue(): mixed
    {
        $email = (string)$this->getContext()->getCheckoutState()->getQuote()->getCustomerEmail();
        if (strlen($email) < 1) {
            return false;
        }

        $isSubscribed = $this->isSubscribed($email);
        if ($isSubscribed) {
            return $isSubscribed;
        }

        if ($this->getContext()->getModuleConfig()->isNewsletterCheckedByDefault()) {
            $this->subscribeEmail($email);
            if ($this->showNotifications()) {
                $message = __('You have been subscribed to the newsletter');
                $this->getContext()->getCoreMessageManager()->addNoticeMessage($message);
            }

            return true;
        }

        return false;
    }

    public function saveValue(mixed $value): void
    {
        $email = (string)$this->getContext()->getCheckoutState()->getQuote()->getCustomerEmail();
        if ((bool)$value) {
            $this->subscribeEmail($email);
            if ($this->showNotifications()) {
                $message = __('You have been subscribed to the newsletter');
                $this->getContext()->getCoreMessageManager()->addNoticeMessage($message);
            }

            return;
        }

        $subscriber = $this->getContext()->getSubscriberFactory()->create();
        $subscriber->load($email, 'subscriber_email'); // @phpstan-ignore-line
        if (!$subscriber->getId() > 0) {
            return;
        }

        if ($this->getContext()->getCustomerSession()->isLoggedIn()) {
            $this->unsubscriberCustomer();

            return;
        }

        $this->unsubscribeEmail($email, $subscriber);

        if ($this->showNotifications()) {
            $message = __('You have been unsubscribed from the newsletter');
            $this->getContext()->getCoreMessageManager()->addNoticeMessage($message);
        }
    }

    private function isSubscribed(string $email): bool
    {
        if ($this->getContext()->getCustomerSession()->isLoggedIn()) {
            return $this->isCustomerSubscribed();
        }

        return $this->isGuestSubscribed($email);
    }

    private function subscribeEmail(string $email)
    {
        $this->getContext()->getSubscriptionManager()->subscribe($email, $this->getStoreId());
    }

    private function unsubscribeEmail(string $email, Subscriber $subscriber): void
    {
        $confirmCode = $subscriber->getSubscriberConfirmCode();
        $this->getContext()->getSubscriptionManager()->unsubscribe($email, $this->getStoreId(), $confirmCode);
    }

    private function unsubscriberCustomer()
    {
        $customerId = (int)$this->getContext()->getCustomerSession()->getCustomerId();
        $this->getContext()->getSubscriptionManager()->unsubscribeCustomer($customerId, $this->getStoreId());
    }

    private function getStoreId(): int
    {
        return (int)$this->getContext()->getCheckoutState()->getQuote()->getStoreId();
    }

    private function isCustomerSubscribed(): bool
    {
        $customerId = (int)$this->getContext()->getCustomerSession()->getCustomerId();
        return $this->getContext()->getNewsletterRepository()->isCustomerIdSubscribed($customerId);
    }

    private function isGuestSubscribed(string $email): bool
    {
        return $this->getContext()->getNewsletterRepository()->isGuestEmailSubscribed($email);
    }

    private function showNotifications(): bool
    {
        $showModifications = $this->getViewModel()->getBlock()->getDisableNotifications();
        if (is_bool($showModifications)) {
            return $showModifications;
        }

        return true;
    }
}
