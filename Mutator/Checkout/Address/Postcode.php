<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Checkout\Address;

use Yireo\LokiCheckout\Mutator\Form\Field\AddressField;

class Postcode extends AddressField
{
    protected array $validators = ['postcode'];
    protected array $filters = ['uppercase'];
}
