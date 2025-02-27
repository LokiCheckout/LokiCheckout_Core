<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\CouponCode;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;

/**
 * @method CheckoutContext getContext()
 * @method CouponCodeRepository getRepository()
 */
class CouponCodeViewModel extends CheckoutViewModel
{
    public function getFieldLabel(): string
    {
        return 'Coupon code';
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutCouponCodeComponent';
    }
}
