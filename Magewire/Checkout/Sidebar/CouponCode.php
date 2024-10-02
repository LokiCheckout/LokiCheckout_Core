<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\Sidebar;

use Yireo\MagewireCheckout\Magewire\AbstractComponent;

class CouponCode extends AbstractComponent
{
    public string $couponCode = '';

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
