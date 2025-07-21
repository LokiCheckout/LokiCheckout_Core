<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Address\Fax;

use LokiCheckout\Core\Component\Base\AddressField\AddressFieldViewModel;

class FaxViewModel extends AddressFieldViewModel
{
    public function getInputType(): string
    {
        return 'tel';
    }
}
