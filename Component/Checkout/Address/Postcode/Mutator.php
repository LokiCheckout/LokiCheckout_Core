<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Postcode;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldMutator;

class Mutator extends AddressFieldMutator
{
    protected array $validators = ['postcode']; // @todo: Borrow from component instead
    protected array $filters = ['uppercase'];
}
