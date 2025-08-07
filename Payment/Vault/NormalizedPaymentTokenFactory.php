<?php declare(strict_types=1);

namespace LokiCheckout\Core\Payment\Vault;

use Magento\Framework\ObjectManagerInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;

class NormalizedPaymentTokenFactory
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
    ) {
    }

    public function create(PaymentTokenInterface $paymentToken): NormalizedPaymentToken
    {
        return $this->objectManager->create(NormalizedPaymentToken::class, [
            'paymentToken' => $paymentToken,
        ]);
    }
}
