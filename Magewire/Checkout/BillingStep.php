<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Magento\Framework\DataObject\Copy;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Step\StepInterface;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingStep extends Component implements StepInterface
{
    protected $listeners = ['billingStepSave' => 'refresh'];

    public bool $billingAsShipping = true;
    public string $refreshToken = '';

    public function __construct(
        private readonly Config $config,
        private readonly ValidatorInterface $stepValidator,
        private readonly BillingAddressState $billingAddressState,
        private readonly Copy $copyService
    ) {
    }

    public function boot(): void
    {
        $this->billingAsShipping = (bool)$this->billingAddressState->getBillingAddress()->getSameAsBilling();
        if (false === $this->enableBillingAsShipping()) {
            $this->billingAsShipping = false;
        }
    }

    public function enableBillingAsShipping(): bool
    {
        return $this->config->enableSameAsBilling();
    }

    public function updatedBillingAsShipping(mixed $billingAsShipping)
    {
        $billingAsShipping = (bool)$billingAsShipping;
        $billingAddress = $this->billingAddressState->getBillingAddress();
        $quote = $billingAddress->getQuote();
        $shippingAddress = $quote->getShippingAddress();

        $billingAddress->setSameAsBilling((int)$billingAsShipping);

        if (true === $billingAsShipping) {
            $this->copyService->copyFieldsetToTarget(
                'customer_address',
                'to_quote_address',
                $shippingAddress,
                $billingAddress
            );

            if ($shippingAddress->getCustomerAddressId()) {
                $billingAddress->setCustomerAddressId($shippingAddress->getCustomerAddressId());
            }
        }

        $quote->save();
        $this->emit('billingStepSave');

        return $billingAsShipping;
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
