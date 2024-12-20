<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Customer;

use Yireo\LokiCheckout\Component\Form\Field\QuoteField;
use Yireo\LokiCheckout\Component\SetterInjection\InjectCustomerSession;

class CustomerEmail extends QuoteField
{
    use InjectCustomerSession;

    public string $email = '';

    protected array $validators = ['email'];

    public function getFieldLabel(): string
    {
        return 'Email';
    }

    public function getInputType(): string
    {
        return 'email';
    }

    public function isAllowRendering(): bool
    {
        if ($this->customerSession->isLoggedIn()) {
            return false;
        }

        return true;
    }

    public function isRequired(): bool
    {
        return true;
    }
}
