<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\CouponCode;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Loki\Field\Component\Base\Field\FieldRepository;

/**
 * @method CheckoutContext getContext()
 */
class CouponCodeRepository extends FieldRepository
{
    public function getValue(): mixed
    {
        // @todo: Double-check that this matches Magento core logic
        // @todo: Move this into a filter "coupon_code"
        return $this->filter((string)$this->getContext()->getCheckoutState()->getQuote()->getCouponCode());
    }

    public function saveValue(mixed $value): void
    {
        if (empty($value['task']) || !isset($value['code'])) {
            return;
        }

        $quote = $this->getContext()->getCheckoutState()->getQuote();
        if ($value['task'] === 'apply') {
            $couponCode = $this->filter($value['code']);
            $quote->setCouponCode($couponCode);
            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote->collectTotals();

            $actualCouponCode = $this->getValue();
            if ($couponCode !== $actualCouponCode) {
                $this->getGlobalMessageRegistry()->addWarning((string)__('Failed to apply coupon code'));
                return;
            }

            $this->getGlobalMessageRegistry()->addSuccess((string)__('Applied coupon code'));
            return;
        }

        if ($value['task'] === 'remove') {
            $quote->setCouponCode('');
            $this->getGlobalMessageRegistry()->addSuccess((string)__('Removed coupon code'));
        }
    }

    private function filter(string $couponCode): string
    {
        return preg_replace(
            '/([^a-zA-Z0-9\-_]+)/',
            '',
            (string)$couponCode
        );
    }
}

