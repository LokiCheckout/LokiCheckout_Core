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
        $accepted = $this->getCustomerId() > 0
            ? $this->saveCustomerValue($value)
            : $this->saveGuestValue($value);

        // Only remember the choice when it was actually processed, otherwise a failed reCAPTCHA would
        // show the box as checked while nobody was subscribed
        if ($accepted) {
            $this->getNewsletterChoice()->setChoice($this->getCustomerEmail(), $value);
        }
    }

    private function saveCustomerValue(bool $value): bool
    {
        if ($this->isCustomerSubscribed() === $value) {
            return true;
        }

        if (false === $this->assertRecaptcha()) {
            return false;
        }

        if ($value) {
            $this->getContext()->getSubscriptionManager()->subscribeCustomer($this->getCustomerId(), $this->getStoreId());
            $this->addNotification((string)__('You have been subscribed to the newsletter'));

            return true;
        }

        $this->getContext()->getSubscriptionManager()->unsubscribeCustomer($this->getCustomerId(), $this->getStoreId());
        $this->addNotification((string)__('You have been unsubscribed from the newsletter'));

        return true;
    }

    private function saveGuestValue(bool $value): bool
    {
        $email = $this->getCustomerEmail();
        if (strlen($email) < 1) {
            return true;
        }

        $newsletterChoice = $this->getNewsletterChoice();
        if ($value === $newsletterChoice->hasSubscribedEmail($email)) {
            return true;
        }

        if (false === $value) {
            $this->unsubscribeOwnGuestSubscription($email);

            return true;
        }

        if (false === $this->assertRecaptcha()) {
            return false;
        }

        if ($this->getContext()->getNewsletterRepository()->isGuestEmailSubscribed($email)) {
            return true;
        }

        $this->getContext()->getSubscriptionManager()->subscribe($email, $this->getStoreId());
        $newsletterChoice->addSubscribedEmail($email);
        $this->addNotification((string)__('You have been subscribed to the newsletter'));

        return true;
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
        $disableNotifications = $this->getViewModel()->getBlock()->getDisableNotifications();
        if (is_bool($disableNotifications)) {
            return false === $disableNotifications;
        }

        return true;
    }
}
