<?php declare(strict_types=1);

namespace LokiCheckout\Core\Payment\Redirect;

use LokiCheckout\Core\ViewModel\CheckoutState;
use LokiCheckout\Core\Step\FinalStep\RedirectContext;
use Magento\Framework\UrlFactory;

class SessionRedirectResolver implements RedirectResolverInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly UrlFactory $urlFactory,
    ) {
    }

    public function resolve(RedirectContext $redirectContext): false|string
    {
        $redirectUrl = (string)$this->checkoutState->getCheckoutSession()->getRedirectUrl();
        if ($redirectUrl === '' || $redirectUrl === '0') {
            return false;
        }

        if (str_starts_with($redirectUrl, '/') && false === str_starts_with($redirectUrl, '//')) {
            return $this->urlFactory->create()->getDirectUrl(ltrim($redirectUrl, '/'));
        }

        return $redirectUrl;
    }
}
