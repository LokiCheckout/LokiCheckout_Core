<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Quote;

use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\QuoteFieldComponent;

class CustomerGender extends QuoteFieldComponent
{
    use OptionsBehaviour;

    protected array $validators = ['gender'];

    public function getFieldLabel(): string
    {
        return (string)__('Gender');
    }

}
