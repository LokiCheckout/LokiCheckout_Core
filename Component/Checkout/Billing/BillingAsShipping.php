<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Billing;

use Magento\Quote\Api\CartRepositoryInterface;

use Yireo\LokiCheckout\Util\BillingAsShippingService;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\LokiComponents\Component\ComponentInterface;

class BillingAsShipping implements ComponentInterface
{
    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly ShippingAddressState $shippingAddressState,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly BillingAsShippingService $billingAsShippingService
    ) {
    }

    public function isBillingAsShipping(): bool
    {
        $billingAsShipping = $this->billingAsShippingService->getValueFromAddress(
            $this->billingAddressState->getBillingAddress()
        );

        if (true === $billingAsShipping) {
            $this->copyShippingToBilling();
            $this->cartRepository->save($this->billingAddressState->getQuote());
        }

        return $billingAsShipping;
    }

    public function getAlpineData(): array
    {
        return [
            'billingAsShipping' => $this->isBillingAsShipping()
        ];
    }

    public function mutate(array $data = []): void
    {
        if (!isset($data['billingAsShipping'])) {
            return;
        }

        $billingAsShipping = $data['billingAsShipping'];
        $billingAddress = $this->billingAddressState->getBillingAddress();

        $billingAddress->setSameAsBilling((int)$billingAsShipping);
        if (true === $billingAsShipping) {
            $this->copyShippingToBilling();
        }

        $this->cartRepository->save($this->billingAddressState->getQuote());
    }

    private function copyShippingToBilling()
    {
        $this->billingAsShippingService->copy(
            $this->shippingAddressState->getShippingAddress(),
            $this->billingAddressState->getBillingAddress(),
        );
    }
}
