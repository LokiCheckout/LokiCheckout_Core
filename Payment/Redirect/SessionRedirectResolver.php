<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Payment\Redirect;

use Magento\Checkout\Model\Session as CheckoutSession;
use Yireo\LokiCheckout\Step\FinalStep\RedirectContext;

class SessionRedirectResolver implements RedirectResolverInterface
{
    public function __construct(
        private CheckoutSession $checkoutSession,
    ) {
    }

    public function resolve(RedirectContext $redirectContext): false|string
    {
        $redirectUrl = (string)$this->checkoutSession->getRedirectUrl();
        if (!empty($redirectUrl)) {
            return $redirectUrl;
        }

        return false;
    }
}
