<?php declare(strict_types=1);

namespace LokiCheckout\Core\Recaptcha;

use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Bridges the reCAPTCHA component (which validates the token) and the action that needs to be
 * protected (which runs in a later request). A successful verification is stored in the checkout
 * session as a short-lived, single-use pass.
 */
class Guard
{
    private const SESSION_KEY = 'loki_recaptcha_passed';
    private const TTL = 120;

    public function __construct(
        private readonly Config $config,
        private readonly CheckoutSession $checkoutSession,
    ) {
    }

    public function markPassed(string $formId): void
    {
        $passes = $this->getPasses();
        $passes[$formId] = time();
        $this->checkoutSession->setData(self::SESSION_KEY, $passes);
    }

    /**
     * Consume the pass for the given form id.
     */
    public function isPassed(string $formId): bool
    {
        $passes = $this->getPasses();
        if (false === isset($passes[$formId])) {
            return false;
        }

        $passedAt = (int)$passes[$formId];
        unset($passes[$formId]);
        $this->checkoutSession->setData(self::SESSION_KEY, $passes);

        return (time() - $passedAt) <= self::TTL;
    }

    /**
     * Returns true when reCAPTCHA is not configured for this form id, or when the visitor passed
     * the challenge for it.
     */
    public function assertPassed(string $formId): bool
    {
        if (false === $this->config->isEnabledFor($formId)) {
            return true;
        }

        return $this->isPassed($formId);
    }

    /**
     * @return array<string, int>
     */
    private function getPasses(): array
    {
        $passes = $this->checkoutSession->getData(self::SESSION_KEY);

        return is_array($passes) ? $passes : [];
    }
}
