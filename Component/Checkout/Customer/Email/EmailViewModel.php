<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Customer\Email;

use Yireo\LokiCheckout\Component\Base\QuoteField\QuoteFieldViewModel;

class EmailViewModel extends QuoteFieldViewModel
{
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
        return !$this->getContext()->getCustomerSession()->isLoggedIn();
    }

    public function isRequired(): bool
    {
        return true;
    }
}
