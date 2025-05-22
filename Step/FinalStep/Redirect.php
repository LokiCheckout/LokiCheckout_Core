<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step\FinalStep;

use Magento\Framework\UrlFactory;
use Yireo\LokiCheckout\Payment\Redirect\RedirectResolverListing;

class Redirect
{
    public function __construct(
        private readonly RedirectResolverListing $redirectResolverListing,
        private readonly UrlFactory $urlFactory,
        private readonly RedirectContext $redirectContext,
    ) {
    }

    public function get(): string
    {
        if (false === $this->redirectContext->getPaymentMethod()) {
            return $this->getSuccessUrl();
        }

        foreach ($this->redirectResolverListing->getRedirectResolvers() as $redirectResolver) {
            $redirectUrl = $redirectResolver->resolve($this->redirectContext);
            if (strlen($redirectUrl) > 0) {
                return $redirectUrl;
            }
        }

        return $this->getSuccessUrl();
    }

    private function getSuccessUrl(): string
    {
        $url = $this->urlFactory->create();

        return $url->getUrl('checkout/onepage/success');
    }
}
