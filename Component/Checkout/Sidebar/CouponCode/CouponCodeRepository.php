<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\CouponCode;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method CheckoutContext getContext()
 */
class CouponCodeRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        // @todo: Double-check that this matches Magento core logic
        // @todo: Move this into a filter "coupon_code"
        return preg_replace(
            '/([^a-zA-Z0-9\-\_]+)/',
            '',
            (string)$this->getContext()->getCheckoutState()->getQuote()->getCouponCode()
        );
    }

    public function saveValue(mixed $value): void
    {
        if (empty($value['task']) || !isset($value['code'])) {
            return;
        }

        $quote = $this->getContext()->getCheckoutState()->getQuote();
        if ($value['task'] === 'apply') {
            $quote->setCouponCode($value['code']);
            $this->getContext()->getCartRepository()->save($quote);
            $this->getGlobalMessageRegistry()->addSuccess('Applied coupon code');
        }

        if ($value['task'] === 'remove') {
            $quote->setCouponCode('');
            $this->getContext()->getCartRepository()->save($quote);
            $this->getGlobalMessageRegistry()->addSuccess('Removed coupon code');
        }
    }
}
