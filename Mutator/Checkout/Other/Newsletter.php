<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Checkout\Other;

use Magento\Checkout\Model\Session as CheckoutSession;
use Yireo\LokiComponents\Component\Mutator\MutatorInterface;

class Newsletter implements MutatorInterface
{
    public function __construct(
        private CheckoutSession $checkoutSession
    ) {
    }

    public function mutate(array $data = []): void
    {
    }
}
