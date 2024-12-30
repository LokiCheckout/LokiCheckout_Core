<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAsShipping;

use Yireo\LokiCheckout\Component\Base\Field\FieldViewModel;
use Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress\BillingAddressContext;

/**
 * @method BillingAddressContext getContext()
 */
class BillingAsShippingViewModel extends FieldViewModel
{
    public function getFieldLabel(): string
    {
        return 'Shipping as Billing';
    }
}
