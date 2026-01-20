<?php declare(strict_types=1);

namespace LokiCheckout\Core\Payment\Redirect;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Checkout\Model\Session as CheckoutSession;
use LokiCheckout\Core\Step\FinalStep\RedirectContext;

class SessionRedirectResolver implements RedirectResolverInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function resolve(RedirectContext $redirectContext): false|string
    {
        $redirectUrl = (string)$this->checkoutState->getCheckoutSession()->getRedirectUrl();
        if ($redirectUrl !== '' && $redirectUrl !== '0') {
            return $redirectUrl;
        }

        return false;
    }
}
