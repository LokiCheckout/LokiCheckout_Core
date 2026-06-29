<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address;

use Magento\Quote\Model\Quote;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use Magento\Quote\Model\Quote\Address;

/**
 * @method AddressFormContext getContext()
 */
class AddressFormViewModel extends CheckoutViewModel
{
    protected bool $skipValidation = true;

    public function isAllowRendering(): bool
    {
        if ($this->isShippingAddressComponent() && $this->getQuote()->isVirtual()) {
            return false;
        }

        return parent::isAllowRendering();
    }

    public function showView(): bool
    {
        if ($this->isBillingAddressComponent()) {
            if ($this->isSame() && false === $this->isQuoteVirtual()) {
                return true;
            }
        }

        return false;
    }

    public function isSame(): bool
    {
        return $this->getContext()->getSameAddressService()->isSame();
    }

    public function isQuoteVirtual(): bool
    {
        return (bool)$this->getContext()->getCheckoutState()->getQuote()->isVirtual();
    }

    public function isNewAddress(): bool
    {
        return $this->getContext()->isNewAddress($this->getComponent());
    }

    public function showSelectAddress(): bool
    {
        return count($this->getAddresses()) > 0;
    }

    public function getAddresses(): array
    {
        if (false === $this->getContext()->getCustomerSession()->isLoggedIn()) {
            return [];
        }

        return $this->getContext()->getCustomerProvider()->getAddressLines();
    }

    public function getHtml(): string
    {
        /** @var Address $address */
        $address = $this->getContext()->getCurrentAddress($this->getComponent());

        return $this->getContext()->getAddressRenderer()->getHtml($address);
    }

    public function isBillingAddressComponent(): bool
    {
        return $this->getContext()->isBillingAddressComponent($this->getComponent());
    }

    public function isShippingAddressComponent(): bool
    {
        return $this->getContext()->isShippingAddressComponent($this->getComponent());
    }

    private function getQuote(): Quote
    {
        return $this->getContext()->getCheckoutState()->getQuote();
    }
}
