<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Prefix;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldViewModelViewModel;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;

class ViewModel extends AddressFieldViewModelViewModel
{
    use OptionsBehaviour;

    protected array $validators = ['prefix'];

    public function getFieldLabel(): string
    {
        return 'Prefix';
    }
}
