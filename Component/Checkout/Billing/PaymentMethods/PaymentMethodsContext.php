<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\PaymentMethods;

use Magento\Quote\Api\PaymentMethodManagementInterface;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;

class PaymentMethodsContext extends GenericContext
{
    public function getPaymentMethodManagement(): PaymentMethodManagementInterface
    {
        return $this->get(PaymentMethodManagementInterface::class);
    }
}
