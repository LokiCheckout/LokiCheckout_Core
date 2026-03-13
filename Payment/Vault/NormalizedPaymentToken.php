<?php declare(strict_types=1);

namespace LokiCheckout\Core\Payment\Vault;

use Magento\Vault\Api\Data\PaymentTokenInterface;

class NormalizedPaymentToken
{
    public function __construct(
        private readonly PaymentTokenInterface $paymentToken,
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
        return json_decode($this->paymentToken->getTokenDetails(), true);
    }
}
