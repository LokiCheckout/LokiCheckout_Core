<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\CouponCode;

use Yireo\LokiFieldComponents\Component\Base\Field\FieldRepository;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;

/**
 * @method CheckoutContext getContext()
 */
class CouponCodeRepository extends FieldRepository
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

            $couponCode = $this->getValue();
            if (empty($couponCode)) {
                $this->getGlobalMessageRegistry()->addWarning('Failed to apply coupon code');
                return;
            }

            $this->getGlobalMessageRegistry()->addSuccess('Applied coupon code');
            return;
        }

        if ($value['task'] === 'remove') {
            $quote->setCouponCode('');
            $this->getContext()->getCartRepository()->save($quote);
            $this->getGlobalMessageRegistry()->addSuccess('Removed coupon code');
        }
    }
}
