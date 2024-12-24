<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Yireo\LokiComponents\Component\ComponentViewModel;

class NewsletterViewModel extends ComponentViewModel
{
    public function isSubscribed(): bool
    {
        return (bool)$this->getData();
    }
}
