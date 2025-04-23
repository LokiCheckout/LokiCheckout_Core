<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Payment\Vault;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;

class NormalizedPaymentToken
{
    public function __construct(
        private PaymentTokenInterface $paymentToken,
        private SerializerInterface $serializer,
    ) {
    }

    public function getPublicHash(): string
    {
        return $this->paymentToken->getPublicHash();
    }

    public function getType(): string
    {
        return $this->getTokenDetails()['type'] ?? '';
    }

    public function getName(): string
    {
        return $this->getTokenDetails()['name'] ?? '';
    }

    public function getHint(): string
    {
        return $this->getTokenDetails()['maskedCC'] ?? '';
    }

    private function getTokenDetails(): array
    {
        return $this->serializer->unserialize($this->paymentToken->getTokenDetails());
    }
}
