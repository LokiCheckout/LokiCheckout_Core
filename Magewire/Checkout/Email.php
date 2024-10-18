<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Yireo\LokiCheckout\Magewire\Generic\ShippingAddressFieldComponent;

class Email extends ShippingAddressFieldComponent
{
    public string $email = '';

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
}
