<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Magento\Framework\DataObject\Copy;
use Magento\Quote\Api\CartRepositoryInterface;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Step\StepInterface;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;

class BillingStep extends Component implements StepInterface
{
    protected $listeners = ['billingStepSave' => 'refresh'];

    public bool $billingAsShipping = true;
    public string $refreshToken = '';

    public function __construct(
        private readonly Config $config,
        private readonly ValidatorInterface $stepValidator,
        private readonly BillingAddressState $billingAddressState,
        private readonly ShippingAddressState $shippingAddressState,
        private readonly Copy $copyService,
        private CartRepositoryInterface $cartRepository,
        private StepNavigator $stepNavigator
    ) {
    }

    public function boot(): void
    {
        $this->billingAsShipping = (bool)$this->billingAddressState->getBillingAddress()->getSameAsBilling();
        if (false === $this->enableBillingAsShipping()) {
            $this->billingAsShipping = false;
        }

        if ($this->billingAsShipping) {
            $this->copyShippingToBilling();
        }
    }

    public function enableBillingAsShipping(): bool
    {
        if (false === $this->config->enableSameAsBilling()) {
            return false;
        }

        try {
            $this->stepNavigator->getStepByCode('shipping');
        } catch(UnknownStepException) {
            return false;
        }

        return true;
    }

    public function updatedBillingAsShipping(mixed $billingAsShipping)
    {
        $billingAsShipping = (bool)$billingAsShipping;
        $billingAddress = $this->billingAddressState->getBillingAddress();
        $quote = $billingAddress->getQuote();

        $billingAddress->setSameAsBilling((int)$billingAsShipping);
        if ($billingAsShipping) {
            $this->copyShippingToBilling();
        }

        $this->cartRepository->save($this->billingAddressState->getQuote());
        $this->emit('billingStepSave');

        return $billingAsShipping;
    }

    private function copyShippingToBilling()
    {
        $billingAddress = $this->billingAddressState->getBillingAddress();
        $shippingAddress = $this->shippingAddressState->getShippingAddress();

        $this->copyService->copyFieldsetToTarget(
            'customer_address',
            'to_quote_address',
            $shippingAddress,
            $billingAddress
        );

        if ($shippingAddress->getCustomerAddressId()) {
            $billingAddress->setCustomerAddressId($shippingAddress->getCustomerAddressId());
        }

        $this->cartRepository->save($this->billingAddressState->getQuote());
    }

    public function getCode(): string
    {
        return 'billing';
    }

    public function getLabel(): string
    {
        $quote = $this->billingAddressState->getBillingAddress()->getQuote();
        if (false === $quote->getGrandTotal() > 0) {
            return 'Review'; // @todo: Test for this
        }

        return 'Review & Payments';
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function isAccessable(): bool
    {
        return true;
    }

    public function validate(): true|array
    {
        return $this->stepValidator->validate();
    }
}
