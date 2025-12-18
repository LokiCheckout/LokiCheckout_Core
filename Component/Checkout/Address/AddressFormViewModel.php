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
        if ($this->isBillingAddressComponent()
            && $this->getContext()->getCheckoutState()->getQuote()->getGrandTotal() <= 0
        ) {
            // @todo: Check this for free orders
            return false;
        }

        if ($this->isShippingAddressComponent() && $this->getQuote()->isVirtual()) {
            return false;
        }

        return parent::isAllowRendering();
    }

    public function isSame(): bool
    {
        return $this->getContext()->getSameAddressService()->isSame();
    }

    public function isNewAddress(): bool
    {
        return $this->getContext()->isNewAddress($this->getComponent());
    }

    public function showSelectAddress(): bool
    {
        return $this->getAddresses() !== [];
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

    private function getQuote(): Quote
    {
        return $this->getContext()->getCheckoutState()->getQuote();
    }

    private function isBillingAddressComponent(): bool
    {
        return $this->getContext()->isBillingAddressComponent($this->getComponent());
    }

    private function isShippingAddressComponent(): bool
    {
        return $this->getContext()->isShippingAddressComponent($this->getComponent());
    }
}
