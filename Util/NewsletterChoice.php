<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util;

use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Keeps track of the newsletter choice of the current checkout session, so that the subscription
 * state of email addresses not owned by this session is never disclosed or modified.
 */
class NewsletterChoice
{
    public const CHOICE_SESSION_KEY = 'loki_checkout_newsletter_choice';
    public const SUBSCRIBED_EMAILS_SESSION_KEY = 'loki_checkout_newsletter_subscribed_emails';

    public function __construct(
        private readonly CheckoutSession $checkoutSession,
    ) {
    }

    public function getChoice(): ?bool
    {
        $choice = $this->checkoutSession->getData(self::CHOICE_SESSION_KEY);
        if ($choice === null) {
            return null;
        }

        return (bool)$choice;
    }

    public function setChoice(bool $choice): void
    {
        $this->checkoutSession->setData(self::CHOICE_SESSION_KEY, $choice);
    }

    public function hasSubscribedEmail(string $email): bool
    {
        return in_array($this->normalize($email), $this->getSubscribedEmails(), true);
    }

    public function addSubscribedEmail(string $email): void
    {
        $emails = $this->getSubscribedEmails();
        $emails[] = $this->normalize($email);
        $this->checkoutSession->setData(self::SUBSCRIBED_EMAILS_SESSION_KEY, array_values(array_unique($emails)));
    }

    public function removeSubscribedEmail(string $email): void
    {
        $email = $this->normalize($email);
        $emails = array_filter($this->getSubscribedEmails(), fn (string $value) => $value !== $email);
        $this->checkoutSession->setData(self::SUBSCRIBED_EMAILS_SESSION_KEY, array_values($emails));
    }

    public function reset(): void
    {
        $this->checkoutSession->unsetData(self::CHOICE_SESSION_KEY);
        $this->checkoutSession->unsetData(self::SUBSCRIBED_EMAILS_SESSION_KEY);
    }

    /**
     * @return string[]
     */
    private function getSubscribedEmails(): array
    {
        $emails = $this->checkoutSession->getData(self::SUBSCRIBED_EMAILS_SESSION_KEY);

        return is_array($emails) ? array_map('strval', $emails) : [];
    }

    private function normalize(string $email): string
    {
        return strtolower(trim($email, " \f\n\r\t\v\x00"));
    }
}
