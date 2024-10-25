<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponentBehaviour\OptionsBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\FormTextSelectFieldInterface;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

class Prefix extends AddressFieldComponent implements FormTextSelectFieldInterface
{
    use OptionsBehaviour;
}
