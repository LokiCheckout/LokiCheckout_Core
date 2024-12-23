<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Customer\Email;

use Yireo\LokiCheckout\Component\Form\Field\QuoteFieldViewModel;

class ViewModel extends QuoteFieldViewModel
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
