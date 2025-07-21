<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\CouponCode;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;

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
