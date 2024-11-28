<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Billing;

use Magento\Quote\Api\CartRepositoryInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Util\BillingAsShippingService;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class BillingAsShipping extends Component
{
    protected $listeners = [
        'afterSaveBillingAddress' => 'refresh'
    ];

    public bool $billingAsShipping = true;

    public function __construct(
        private readonly BillingAddressState $billingAddressState,
        private readonly ShippingAddressState $shippingAddressState,
        private readonly CartRepositoryInterface $cartRepository,
        private readonly BillingAsShippingService $billingAsShippingService
    ) {
    }

    public function boot(): void
    {
        $this->billingAsShipping = $this->billingAsShippingService->getValueFromAddress(
            $this->billingAddressState->getBillingAddress()
        );

        if (true === $this->billingAsShipping) {
            $this->copyShippingToBilling();
            $this->cartRepository->save($this->billingAddressState->getQuote());
        }
    }

    public function isBillingAsShippingEnabled(): bool
    {
        return $this->billingAsShippingService->isEnabled();
    }

    public function updatedBillingAsShipping(mixed $billingAsShipping)
    {
        $billingAsShipping = (bool)$billingAsShipping;
        $billingAddress = $this->billingAddressState->getBillingAddress();

        $billingAddress->setSameAsBilling((int)$billingAsShipping);
        if (true === $billingAsShipping) {
            $this->copyShippingToBilling();
        }

        $this->cartRepository->save($this->billingAddressState->getQuote());
        $this->emit('afterSaveBillingAddress');

        return $billingAsShipping;
    }

    private function copyShippingToBilling()
    {
        $this->billingAsShippingService->copy(
            $this->shippingAddressState->getShippingAddress(),
            $this->billingAddressState->getBillingAddress(),
        );
    }
}
