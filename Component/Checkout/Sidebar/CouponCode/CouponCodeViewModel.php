<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Sidebar\CouponCode;

use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;

/**
 * @method GenericContext getContext()
 * @method CouponCodeRepository getRepository()
 */
class CouponCodeViewModel extends FieldViewModel
{
    public function getCouponCode(): string
    {
        return (string)$this->getRepository()->get();
    }

    public function getFieldLabel(): string
    {
        return 'Coupon code';
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutCouponCodeComponent';
    }
}
