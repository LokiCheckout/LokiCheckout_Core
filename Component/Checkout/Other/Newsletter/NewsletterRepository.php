<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Newsletter;

use Loki\Field\Component\Base\Field\FieldRepository;
use LokiCheckout\Core\Recaptcha\Config as RecaptchaConfig;
use LokiCheckout\Core\Recaptcha\FormId;
use LokiCheckout\Core\Recaptcha\Guard;
use LokiCheckout\Core\Util\NewsletterChoice;

/**
 * Guests are only able to change subscriptions that were created by their own checkout session.
 * The subscription state of other email addresses is never disclosed. When the newsletter is
 * checked by default, the subscription itself is only created when placing the order.
 *
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

        if ($this->getCustomerId() > 0 && $this->isCustomerSubscribed()) {
            return true;
        }

        $choice = $this->getNewsletterChoice()->getChoice($email);
        if ($choice !== null) {
            return $choice;
        }

        if ($this->getCustomerId() === 0 && $this->getNewsletterChoice()->hasSubscribedEmail($email)) {
            return true;
        }

        return $this->isNewsletterCheckedByDefault() && false === $this->hasCustomerRecord();
    }

    public function saveValue(mixed $value): void
    {
        $value = (bool)$value;
        $this->getNewsletterChoice()->setChoice($this->getCustomerEmail(), $value);

        if ($this->getCustomerId() > 0) {
            $this->saveCustomerValue($value);

            return;
        }

        $this->saveGuestValue($value);
    }

    private function saveCustomerValue(bool $value): void
    {
        if ($this->isCustomerSubscribed() === $value) {
            return;
        }

        if (false === $this->assertRecaptcha()) {
            return;
        }

        if ($value) {
            $this->getContext()->getSubscriptionManager()->subscribeCustomer($this->getCustomerId(), $this->getStoreId());
            $this->addNotification((string)__('You have been subscribed to the newsletter'));

            return;
        }

        $this->getContext()->getSubscriptionManager()->unsubscribeCustomer($this->getCustomerId(), $this->getStoreId());
        $this->addNotification((string)__('You have been unsubscribed from the newsletter'));
    }

    private function saveGuestValue(bool $value): void
    {
        $email = $this->getCustomerEmail();
        if (strlen($email) < 1) {
            return;
        }

        $newsletterChoice = $this->getNewsletterChoice();
        if ($value === $newsletterChoice->hasSubscribedEmail($email)) {
            return;
        }

        if (false === $value) {
            $this->unsubscribeOwnGuestSubscription($email);

            return;
        }

        if (false === $this->assertRecaptcha()) {
            return;
        }

        if ($this->getContext()->getNewsletterRepository()->isGuestEmailSubscribed($email)) {
            return;
        }

        $this->getContext()->getSubscriptionManager()->subscribe($email, $this->getStoreId());
        $newsletterChoice->addSubscribedEmail($email);
        $this->addNotification((string)__('You have been subscribed to the newsletter'));
    }

    private function unsubscribeOwnGuestSubscription(string $email): void
    {
        $confirmCode = $this->getContext()->getNewsletterRepository()->getConfirmationCode($email);
        $this->getContext()->getSubscriptionManager()->unsubscribe($email, $this->getStoreId(), $confirmCode);
        $this->getNewsletterChoice()->removeSubscribedEmail($email);
        $this->addNotification((string)__('You have been unsubscribed from the newsletter'));
    }

    private function assertRecaptcha(): bool
    {
        if ($this->recaptchaGuard->assertPassed(FormId::NEWSLETTER)) {
            return true;
        }

        $this->getLocalMessageRegistry()->addError($this->recaptchaConfig->getValidationFailureMessage());

        return false;
    }

    private function isNewsletterCheckedByDefault(): bool
    {
        return $this->getContext()->getModuleConfig()->isNewsletterCheckedByDefault();
    }

    private function hasCustomerRecord(): bool
    {
        if ($this->getCustomerId() === 0) {
            return false;
        }

        return $this->getContext()->getNewsletterRepository()->hasCustomerIdRecord($this->getCustomerId());
    }

    private function isCustomerSubscribed(): bool
    {
        return $this->getContext()->getNewsletterRepository()->isCustomerIdSubscribed($this->getCustomerId());
    }

    private function getNewsletterChoice(): NewsletterChoice
    {
        return $this->getContext()->getNewsletterChoice();
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

    private function addNotification(string $message): void
    {
        if (false === $this->showNotifications()) {
            return;
        }

        $this->getContext()->getCoreMessageManager()->addNoticeMessage($message);
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
