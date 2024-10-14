<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress;

use Yireo\FriggaCheckout\Magewire\Generic\EavFormFieldComponent\OptionsBehaviour;
use Yireo\FriggaCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\FriggaCheckout\Magewire\Generic\ShippingAddressFormFieldComponent;

class Prefix extends ShippingAddressFormFieldComponent implements FormTextSelectFieldInterface
{
    use OptionsBehaviour;
}
