<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\CouponCode;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method GenericContext getContext()
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
            (string)$this->getContext()->getQuote()->getCouponCode()
        );
    }

    public function saveValue(mixed $value): void
    {
        if (empty($data['task']) || !isset($data['code'])) {
            return;
        }

        $quote = $this->getContext()->getQuote();
        if ($data['task'] === 'apply') {
            $quote->setCouponCode($data['code']);
            $this->getContext()->getCartRepository()->save($quote);
            $this->getGlobalMessageRegistry()->addSuccess('Applied coupon code');
        }

        if ($data['task'] === 'remove') {
            $quote->setCouponCode('');
            $this->getContext()->getCartRepository()->save($quote);
            $this->getGlobalMessageRegistry()->addSuccess('Removed coupon code');
        }
    }
}
