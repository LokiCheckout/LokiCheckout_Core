<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Telephone;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldViewModel;

class TelephoneViewModel extends AddressFieldViewModel
{
    public function getInputType(): string
    {
        return 'tel';
    }
}
