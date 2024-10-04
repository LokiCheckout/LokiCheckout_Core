<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\Sidebar;

use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;

class CouponCode extends Component
{
    public string $couponCode = '';

    public function __construct(
        private CheckoutState $checkoutState
    ) {
    }

    public function applyCouponCode(): void
    {
        $this->checkoutState->getQuote()->setCouponCode($this->getCouponCode());
    }

    public function removeCouponCode(): void
    {
        $this->checkoutState->getQuote()->setCouponCode('');
    }

    private function getCouponCode(): string
    {
        // @todo: Double-check that this matches Magento core logic
        return preg_replace('/([^a-zA-Z0-9\-\_]+)/', '', $this->couponCode);
    }
}
