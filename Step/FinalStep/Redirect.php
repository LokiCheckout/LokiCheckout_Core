<?php declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep;

use LokiCheckout\Core\Payment\Redirect\SessionRedirectResolver;
use Magento\Framework\UrlFactory;
use LokiCheckout\Core\Payment\Redirect\RedirectResolverListing;

class Redirect
{
    public function __construct(
        private readonly RedirectResolverListing $redirectResolverListing,
        private readonly UrlFactory $urlFactory,
        private readonly RedirectContext $redirectContext,
        private readonly SessionRedirectResolver $sessionRedirectResolver,
    ) {
    }

    public function get(): string
    {
        if (false === $this->redirectContext->getPaymentMethod()) {
            return $this->getSuccessUrl();
        }

        foreach ($this->redirectResolverListing->getRedirectResolvers() as $redirectResolver) {
            $redirectUrl = $redirectResolver->resolve($this->redirectContext);
            if (false === $redirectUrl) {
                continue;
            }

            if (strlen($redirectUrl) > 0) {
                return $redirectUrl;
            }
        }

        $redirectUrl = $this->sessionRedirectResolver->resolve($this->redirectContext);
        if (is_string($redirectUrl) && $redirectUrl !== '') {
            return $redirectUrl;
        }

        return (string)$this->getSuccessUrl();
    }

    private function getSuccessUrl(): string
    {
        $url = $this->urlFactory->create();

        return $url->getUrl('checkout/onepage/success');
    }
}
