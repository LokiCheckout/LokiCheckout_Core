<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;

class NewsletterViewModel extends FieldViewModel
{
    public function getFieldLabel(): string
    {
        return 'Newsletter';
    }
}
