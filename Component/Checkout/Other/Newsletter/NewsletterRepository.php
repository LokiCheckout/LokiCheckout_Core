<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Newsletter;

use Loki\Field\Component\Base\Field\FieldRepository;
use LokiCheckout\Core\Recaptcha\Config as RecaptchaConfig;
use LokiCheckout\Core\Recaptcha\FormId;
use LokiCheckout\Core\Recaptcha\Guard;
use Magento\Newsletter\Model\Subscriber;

/**
 * @method NewsletterContext getContext()
 */
class NewsletterRepository extends FieldRepository
{
    public function __construct(
        private readonly Guard $recaptchaGuard,
        private readonly RecaptchaConfig $recaptchaConfig,
    ) {
    }

    public function getValue(): mixed
    {
        $email = $this->getCustomerEmail();
        if (strlen($email) < 1) {
            return false;
        }

        if (true === $this->isSubscribed()) {
            return true;
        }

        if ($this->isNewsletterCheckedByDefault() && false === $this->hasSubscriberRecord()) {
            $this->subscribe();
            if ($this->showNotifications()) {
                $message = __('You have been subscribed automatically to the newsletter');
                $this->getContext()->getCoreMessageManager()->addNoticeMessage($message);
            }

            return true;
        }

        return false;
    }

    private function isNewsletterCheckedByDefault(): bool
    {
        return $this->getContext()->getModuleConfig()->isNewsletterCheckedByDefault();
    }

    public function saveValue(mixed $value): void
    {
        $value = (bool)$value;
        $isSubscribed = $this->isSubscribed();
        if (true === $isSubscribed && true === $value) {
            return;
        }

        $needsOptOut = false;
        if (false === $isSubscribed && false === $value) {
            if (false === $this->isNewsletterCheckedByDefault() || $this->hasSubscriberRecord()) {
                return;
            }

            $needsOptOut = true;
        }

        if (false === $this->recaptchaGuard->assertPassed(FormId::NEWSLETTER)) {
            $this->getLocalMessageRegistry()->addError($this->recaptchaConfig->getValidationFailureMessage());

            return;
        }

        if ($needsOptOut) {
            $this->saveOptOut();

            return;
        }

        if (true === $value) {
            $this->subscribeEmail();
            if ($this->showNotifications()) {
                $message = __('You have been subscribed to the newsletter');
                $this->getContext()->getCoreMessageManager()->addNoticeMessage($message);
            }

            return;
        }

        if ($this->getContext()->getCustomerSession()->isLoggedIn()) {
            $this->unsubscribeCustomer();

            return;
        }

        $this->unsubscribeEmail();

        if ($this->showNotifications()) {
            $message = __('You have been unsubscribed from the newsletter');
            $this->getContext()->getCoreMessageManager()->addNoticeMessage($message);
        }
    }

    private function isSubscribed(): bool
    {
        $customerId = $this->getCustomerId();
        if ($customerId > 0) {
            return $this->isCustomerSubscribed();
        }

        return $this->isGuestSubscribed();
    }

    private function hasSubscriberRecord(): bool
    {
        $customerSession = $this->getContext()->getCustomerSession();
        if ($customerSession->isLoggedIn()) {
            return $this->getContext()->getNewsletterRepository()->hasCustomerIdRecord(
                (int)$customerSession->getCustomerId()
            );
        }

        $email = (string)$this->getContext()->getCheckoutState()->getQuote()->getCustomerEmail();
        return $this->getContext()->getNewsletterRepository()->hasGuestEmailRecord(
            $email
        );
    }

    private function subscribe(): void
    {
        $customerId = $this->getCustomerId();
        if ($customerId > 0) {
            $this->subscribeCustomer();
            return;
        }

        $this->subscribeEmail();
    }

    private function subscribeEmail()
    {
        $this->getContext()->getSubscriptionManager()->subscribe(
            $this->getCustomerEmail(),
            $this->getStoreId()
        );
    }

    private function saveOptOut(): void
    {
        if ($this->getCustomerId() > 0) {
            $this->unsubscribeCustomer();

            return;
        }

        $subscriber = $this->getContext()->getSubscriberFactory()->create();
        $subscriber->setSubscriberEmail($this->getCustomerEmail());
        $subscriber->setStoreId($this->getStoreId());
        $subscriber->setSubscriberConfirmCode($subscriber->randomSequence());
        $subscriber->setStatus(Subscriber::STATUS_UNSUBSCRIBED);
        $subscriber->save(); // @phpstan-ignore-line
    }

    private function unsubscribeEmail(): void
    {
        $email = $this->getCustomerEmail();
        $subscriber = $this->getContext()->getSubscriberFactory()->create();
        $subscriber->load($email, 'subscriber_email'); // @phpstan-ignore-line
        $confirmCode = $subscriber->getSubscriberConfirmCode();
        $this->getContext()->getSubscriptionManager()->unsubscribe($email, $this->getStoreId(), $confirmCode);
    }

    private function subscribeCustomer()
    {
        $customerId = (int)$this->getContext()->getCustomerSession()->getCustomerId();
        $this->getContext()->getSubscriptionManager()->subscribeCustomer($customerId, $this->getStoreId());
    }

    private function unsubscribeCustomer()
    {
        $customerId = (int)$this->getContext()->getCustomerSession()->getCustomerId();
        $this->getContext()->getSubscriptionManager()->unsubscribeCustomer($customerId, $this->getStoreId());
    }

    private function getStoreId(): int
    {
        return (int)$this->getContext()->getCheckoutState()->getQuote()->getStoreId();
    }

    private function getCustomerEmail(): string
    {
        return (string)$this->getContext()->getCheckoutState()->getQuote()->getCustomerEmail();
    }

    private function getCustomerId(): int
    {
        if (!$this->getContext()->getCustomerSession()->isLoggedIn()) {
            return 0;
        }

        return (int)$this->getContext()->getCustomerSession()->getCustomerId();
    }

    private function isCustomerSubscribed(): bool
    {
        $customerId = (int)$this->getContext()->getCustomerSession()->getCustomerId();
        return $this->getContext()->getNewsletterRepository()->isCustomerIdSubscribed($customerId);
    }

    private function isGuestSubscribed(): bool
    {
        $email = $this->getCustomerEmail();
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
