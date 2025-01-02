<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Base\Generic\GenericViewModel;

/**
 * @method BillingAddressContext getContext()
 */
class BillingAddressViewModel extends GenericViewModel
{
    public function isEnabled(): bool
    {
        return $this->getContext()->getQuote()->getGrandTotal() > 0; // @todo: Check how this goes
    }

    public function isSameAddress(): bool
    {
        return $this->getContext()->getSameAddressService()->isEnabled();
    }

    public function isNewAddress(): bool
    {
        return $this->getContext()->isNewAddress();
    }

    public function getHtml(): string
    {
        $address = $this->getContext()->getBillingAddressState()->getBillingAddress();
        return $this->getContext()->getAddressRenderer()->getHtml($address);
    }
}
