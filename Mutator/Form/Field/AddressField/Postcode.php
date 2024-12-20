<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Form\Field\AddressField;

use Yireo\LokiCheckout\Mutator\Form\Field\AddressField;

abstract class Postcode extends AddressField
{
    protected array $validators = ['postcode'];
    protected array $filters = ['uppercase'];
}
