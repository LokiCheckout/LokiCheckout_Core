<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;

class NewsletterViewModel extends FieldViewModel
{
    public function getFieldLabel(): string
    {
        return 'Sign up for our newsletter';
    }


    public function isDisabled(): bool
    {
        $email = $this->getContext()->getCheckoutState()->getQuote()->getCustomerEmail();
        if (empty($email)) {
            return true;
        }

        return false;
    }
}
