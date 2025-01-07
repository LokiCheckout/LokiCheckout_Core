<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing\PaymentMethods;

use Magento\Quote\Api\PaymentMethodManagementInterface;
use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiComponents\Component\Behaviour\InheritFromParentContext;
use Yireo\LokiComponents\Component\ComponentContextInterface;

class PaymentMethodsContext implements ComponentContextInterface
{
    use InheritFromParentContext;

    public function __construct(
        private readonly GenericContext $parentContext,
        private readonly PaymentMethodManagementInterface $paymentMethodManagement,
    ) {
    }

    public function getPaymentMethodManagement(): PaymentMethodManagementInterface
    {
        return $this->paymentMethodManagement;
    }
}
