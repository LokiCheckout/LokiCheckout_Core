<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Base\Generic\GenericViewModel;

/**
 * @method BillingAddressContext getContext()
 */
class BillingAddressViewModel extends GenericViewModel
{
    public function getBillingAsShippingValue(): bool
    {
        return $this->getContext()->billingAsShippingService->getValueFromAddress(
            $this->getContext()->billingAddressState->getBillingAddress()
        );
    }

    public function isEnabled(): bool
    {
        return $this->getContext()->billingAddressState->getQuote()->getGrandTotal() > 0; // @todo: Check how this goes
    }

    public function getHtml(): string
    {
        $address = $this->getContext()->billingAddressState->getBillingAddress();
        return $this->getContext()->addressRenderer->getHtml($address);
    }
}
