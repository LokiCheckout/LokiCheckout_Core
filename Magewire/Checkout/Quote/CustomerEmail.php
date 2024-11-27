<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Quote;

use Yireo\LokiCheckout\Magewire\Form\Field\QuoteFieldComponent;

class CustomerEmail extends QuoteFieldComponent
{
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

    public function isRequired(): bool
    {
        return true;
    }

    public function isVisible(): bool
    {
        return false === $this->customerSession->isLoggedIn();
    }
}
