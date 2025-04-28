<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;

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
