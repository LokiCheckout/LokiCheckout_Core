<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\BillingAddress;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiCheckout\Util\AddressRenderer;
use Yireo\LokiCheckout\Util\BillingAsShippingService;

class BillingAddressContext extends GenericContext
{
    public function getBillingAsShippingService(): BillingAsShippingService
    {
        return $this->get(BillingAsShippingService::class);
    }

    public function getAddressRenderer(): AddressRenderer
    {
        return $this->get(AddressRenderer::class);
    }
}
