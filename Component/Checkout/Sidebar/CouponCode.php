<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar;

use Yireo\LokiCheckout\ViewModel\CheckoutState;
use Yireo\LokiComponents\Component\MutableComponentInterface;

class CouponCode implements MutableComponentInterface
{
    public function __construct(
        private CheckoutState $checkoutState
    ) {
    }

    public function getCouponCode(): string
    {
        // @todo: Double-check that this matches Magento core logic
        return preg_replace('/([^a-zA-Z0-9\-\_]+)/', '', (string)$this->checkoutState->getQuote()->getCouponCode());
    }

    public function mutate(array $data = []): void
    {
        if (empty($data['action']) || empty($data['code'])) {
            return;
        }

        if ($data['action'] === 'apply') {
            $this->checkoutState->getQuote()->setCouponCode($data['code']);
        }

        if ($data['action'] === 'remove') {
            $this->checkoutState->getQuote()->setCouponCode('');
        }
    }
}
