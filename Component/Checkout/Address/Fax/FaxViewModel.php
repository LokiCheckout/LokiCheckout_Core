<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Fax;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;

class FaxViewModel extends AddressFieldViewModel
{
    public function getInputType(): string
    {
        return 'tel';
    }
}
