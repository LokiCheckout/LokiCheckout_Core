<?php 

declare(strict_types=1);

namespace LokiCheckout\Core\Payment\Vault;

use Magento\Framework\ObjectManagerInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;

class NormalizedPaymentTokenFactory
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
    ) {
    }

    // @phpstan-ignore-next-line
    public function create(PaymentTokenInterface $paymentToken): NormalizedPaymentToken
    {
        /** @var NormalizedPaymentToken $token */
        // @phpstan-ignore-next-line
        $token = $this->objectManager->create(
            NormalizedPaymentToken::class,
            ['paymentToken' => $paymentToken]
        );

        return $token;
    }
}
