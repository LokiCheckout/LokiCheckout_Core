<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Telephone;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldViewModelViewModel;

class ViewModel extends AddressFieldViewModelViewModel
{
    public function getInputType(): string
    {
        return 'tel';
    }
}
