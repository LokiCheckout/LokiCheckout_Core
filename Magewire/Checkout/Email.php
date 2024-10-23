<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Magento\Customer\Model\Session as CustomerSession;
use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponent;

class Email extends ShippingAddressFieldComponent
{
    public string $email = '';

    protected array $validators = ['email'];

    public function __construct(
        private CustomerSession $customerSession
    ) {
    }

    public function getFieldLabel(): string
    {
        return 'Email';
    }

    public function getInputType(): string
    {
        return 'email';
    }

    public function isRequired(): bool
    {
        return true;
    }

    public function isVisible(): bool
    {
        return false === $this->customerSession->isLoggedIn();
    }
}
