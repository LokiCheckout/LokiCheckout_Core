<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\CountryId;

use Yireo\LokiCheckout\Component\Form\AddressField\AddressFieldViewModel;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;

class CountryIdViewModel extends AddressFieldViewModel
{
    use OptionsBehaviour; // @todo: Inject the right Options model right here?
}
