<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Prefix;

use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Component\Form\AddressField\AddressFieldViewModel;

class PrefixViewModel extends AddressFieldViewModel
{
    use OptionsBehaviour;

    public function getFieldLabel(): string
    {
        return 'Prefix';
    }
}
