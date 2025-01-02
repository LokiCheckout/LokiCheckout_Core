<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Customer\Gender;

use Yireo\LokiCheckout\Component\Base\QuoteField\QuoteFieldViewModel;

class GenderViewModel extends QuoteFieldViewModel
{
    public function getFieldLabel(): string
    {
        return (string)__('Gender');
    }
}
