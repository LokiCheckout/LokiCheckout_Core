<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Generic;

use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class GenericContext implements ComponentContextInterface
{
    public function __construct(
        public readonly CheckoutState $checkoutState
    ) {
    }
}
