<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Quote;

use Yireo\LokiCheckout\Magewire\Form\Field\QuoteFieldComponent;
use Yireo\LokiCheckout\Magewire\SetterInjection\InjectCustomerSession;

class CustomerEmail extends QuoteFieldComponent
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

    public function isRequired(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if ($this->customerSession->isLoggedIn()) {
            return true;
        }

        return $this->valid;
    }

    public function isVisible(): bool
    {
        return false === $this->customerSession->isLoggedIn();
    }
}
