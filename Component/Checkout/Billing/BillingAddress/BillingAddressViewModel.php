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
        return $this->getContext()->getBillingAsShippingService()->getValueFromAddress(
            $this->getContext()->getBillingAddressState()->getBillingAddress()
        );
    }

    public function isEnabled(): bool
    {
        return $this->getContext()->getBillingAddressState()->getQuote()->getGrandTotal() > 0; // @todo: Check how this goes
    }

    public function getHtml(): string
    {
        $address = $this->getContext()->getBillingAddressState()->getBillingAddress();
        return $this->getContext()->getAddressRenderer()->getHtml($address);
    }
}
