<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Checkout;

use LokiCheckout\Core\ViewModel\CheckoutState;

class UpdateShippingMethod
{
    public function __construct(
        private CheckoutState $checkoutState,
    ){
    }

    public function execute(string $shippingMethodCode): void
    {
        $quote = $this->checkoutState->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        if (strlen($shippingMethodCode) === 0) {
            $shippingAddress->removeAllShippingRates();
            $shippingAddress->setShippingMethod('');

            return;
        }

        $shippingAddress->setShippingMethod($shippingMethodCode);
        $shippingAddress->setCollectShippingRates(true);
        $shippingAddress->collectShippingRates();
        $this->checkoutState->saveQuote($quote);

        $quote->setTotalsCollectedFlag(false);
        $quote->collectTotals();
        $shippingAddress->setShippingMethod($shippingMethodCode);

        $this->checkoutState->saveQuote($quote);
    }
}
