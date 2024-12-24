<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\CountryId;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;
use Yireo\LokiCheckout\Component\Base\Field\FieldBehaviour\OptionsBehaviour;

class CountryIdViewModel extends AddressFieldViewModel
{
    use OptionsBehaviour; // @todo: Inject the right Options model right here?
}
