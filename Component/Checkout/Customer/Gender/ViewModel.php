<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Customer\Gender;

use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Component\Form\Field\QuoteFieldViewModel;

class ViewModel extends QuoteFieldViewModel
{
    use OptionsBehaviour;

    protected array $validators = ['gender'];

    public function getFieldLabel(): string
    {
        return (string)__('Gender');
    }
}
