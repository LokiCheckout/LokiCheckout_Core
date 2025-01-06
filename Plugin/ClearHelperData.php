<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Checkout\Model\Session as CheckoutSession;

class ClearHelperData
{
    public function afterClearQuote(CheckoutSession $checkoutSession)
    {
        $this->resetLokiCheckout($checkoutSession);
    }

    public function afterClearStorage(CheckoutSession $checkoutSession)
    {
        $this->resetLokiCheckout($checkoutSession);
    }

    public function afterClearHelperData(CheckoutSession $checkoutSession)
    {
        $this->resetLokiCheckout($checkoutSession);
    }

    public function afterResetCheckout(CheckoutSession $checkoutSession)
    {
        $this->resetLokiCheckout($checkoutSession);
    }

    private function resetLokiCheckout(CheckoutSession $checkoutSession)
    {
        $checkoutSession->setCurrentStep(null);
        $checkoutSession->setCustomerAccountType(null);
    }
}
