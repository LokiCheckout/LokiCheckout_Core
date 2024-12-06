<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Quote;

use Yireo\LokiCheckout\Magewire\Form\Field\QuoteFieldComponent;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectCustomerSession;

class CustomerEmail extends QuoteFieldComponent
{
    use InjectCustomerSession;

    public string $email = '';

    protected array $validators = ['email'];

    public function boot(): void
    {
        if ($this->customerSession->isLoggedIn()) {
            $this->visible = false;
        }

        parent::boot();
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
}
