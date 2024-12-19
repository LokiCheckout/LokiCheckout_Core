<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Quote;

use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Component\Form\Field\QuoteField;

class CustomerGender extends QuoteField
{
    use OptionsBehaviour;

    protected array $validators = ['gender'];

    public function getFieldLabel(): string
    {
        return (string)__('Gender');
    }
}
