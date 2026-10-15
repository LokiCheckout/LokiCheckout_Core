<?php declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\UrlFactory;

/**
 * Single-use token that protects the finalize URL. Unlike the form key, this token is safe to
 * hand to a third party (like a PSP using it as return URL), because it only authorizes the
 * finalize action once and is rotated after use.
 */
class FinalizeToken
{
    public const PARAM_NAME = 'token';
    private const SESSION_KEY = 'loki_checkout_finalize_token';

    public function __construct(
        private readonly CheckoutSession $checkoutSession,
        private readonly UrlFactory $urlFactory,
    ) {
    }

    public function getToken(): string
    {
        $token = (string)$this->checkoutSession->getData(self::SESSION_KEY);
        if ($token !== '') {
            return $token;
        }

        $token = bin2hex(random_bytes(32));
        $this->checkoutSession->setData(self::SESSION_KEY, $token);

        return $token;
    }

    public function getUrl(array $params = []): string
    {
        $params[self::PARAM_NAME] = $this->getToken();

        return $this->urlFactory->create()->getUrl('loki_checkout/index/finalize', ['_query' => $params]);
    }

    /**
     * Validate and consume the token
     */
    public function validate(string $token): bool
    {
        $sessionToken = (string)$this->checkoutSession->getData(self::SESSION_KEY);
        if ($sessionToken === '' || $token === '') {
            return false;
        }

        if (false === hash_equals($sessionToken, $token)) {
            return false;
        }

        $this->checkoutSession->unsetData(self::SESSION_KEY);

        return true;
    }
}
