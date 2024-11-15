<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponentBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Form\Field\FormTextSelectFieldInterface;

class Prefix extends AddressFieldComponent implements FormTextSelectFieldInterface
{
    use OptionsBehaviour;

    protected array $validators = ['prefix'];

    public function getFieldLabel(): string
    {
        return 'Prefix';
    }
}
