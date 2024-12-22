<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Other\Newsletter;

use Magento\Checkout\Model\Session as CheckoutSession;
use Yireo\LokiComponents\Component\Mutator\Mutator as ParentMutator;

class Mutator extends ParentMutator
{
    public function __construct(
        private CheckoutSession $checkoutSession
    ) {
    }

    public function mutate(array $data = []): void
    {
    }
}
