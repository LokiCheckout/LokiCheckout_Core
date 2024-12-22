<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing;

use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Component\ViewModel\ViewModelInterface;

class BillingAddressView implements ViewModelInterface
{
    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly AddressRenderer $addressRenderer
    ) {
    }

    public function getHtml(): string
    {
        $address = $this->billingAddressState->getBillingAddress();
        return $this->addressRenderer->getHtml($address);
    }
}
