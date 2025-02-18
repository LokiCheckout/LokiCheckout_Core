<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Magento\Checkout\Model\Session as CheckoutSession;

class ClearHelperDataPlugin
{
    public function afterClearQuote(CheckoutSession $checkoutSession): CheckoutSession
    {
        $this->resetLokiCheckout($checkoutSession);
        return $checkoutSession;
    }

    public function afterClearStorage(CheckoutSession $checkoutSession): CheckoutSession
    {
        $this->resetLokiCheckout($checkoutSession);
        return $checkoutSession;
    }

    public function afterClearHelperData(CheckoutSession $checkoutSession)
    {
        $this->resetLokiCheckout($checkoutSession);
    }

    public function afterResetCheckout(CheckoutSession $checkoutSession): CheckoutSession
    {
        $this->resetLokiCheckout($checkoutSession);
        return $checkoutSession;
    }

    public function afterReplaceQuote(CheckoutSession $checkoutSession): CheckoutSession
    {
        $this->resetLokiCheckout($checkoutSession);
        return $checkoutSession;
    }

    private function resetLokiCheckout(CheckoutSession $checkoutSession)
    {
        $checkoutSession->setCurrentStep(null);
        $checkoutSession->setCustomerAccountType(null);
    }
}
