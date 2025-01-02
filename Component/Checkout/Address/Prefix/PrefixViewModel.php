<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Prefix;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;

class PrefixViewModel extends AddressFieldViewModel
{
    public function getFieldLabel(): string
    {
        return 'Prefix';
    }
}
