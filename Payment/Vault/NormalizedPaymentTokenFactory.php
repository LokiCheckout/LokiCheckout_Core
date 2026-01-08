<?php declare(strict_types=1);

/** @phpstan-ignore-file */

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
        /** @var NormalizedPaymentToken $token */
        $token = $this->objectManager->create(
            NormalizedPaymentToken::class,
            ['paymentToken' => $paymentToken]
        );

        return $token;
    }
}
