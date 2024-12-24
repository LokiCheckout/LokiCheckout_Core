<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Telephone;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;

class TelephoneViewModel extends AddressFieldViewModel
{
    public function getInputType(): string
    {
        return 'tel';
    }
}
