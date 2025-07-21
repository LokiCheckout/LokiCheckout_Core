<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Newsletter;

use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;

class NewsletterViewModel extends CheckoutViewModel
{
    public function getFieldLabel(): string
    {
        return 'Newsletter';
    }

    public function getInputLabel(): string
    {
        return 'Sign up for our newsletter';
    }

    public function isDisabled(): bool
    {
        $email = $this->getContext()->getCheckoutState()->getQuote()->getCustomerEmail();
        return empty($email);
    }
}
