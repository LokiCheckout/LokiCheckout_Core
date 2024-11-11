<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Step\FinalStep;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\UrlFactory;
use Magento\Payment\Model\MethodInterface as PaymentMethod;
use Magento\Sales\Api\Data\OrderInterface;

class RedirectUrl
{
    public function __construct(
        private CheckoutSession $checkoutSession,
        private UrlFactory $urlFactory
    ) {
    }

    public function get(PaymentMethod $paymentMethod, ?OrderInterface $order = null): string
    {
        $redirectUrl = (string)$this->checkoutSession->getRedirectUrl();
        if (!empty($redirectUrl)) {
            return $redirectUrl;
        }

        $url = $this->urlFactory->create();

        return $url->getUrl('checkout/onepage/success');
    }
}
