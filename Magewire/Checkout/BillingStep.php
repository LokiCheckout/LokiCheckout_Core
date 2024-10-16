<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Magento\Framework\DataObject\Copy;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Config\Config;
use Yireo\LokiCheckout\Step\StepInterface;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

class BillingStep extends Component implements StepInterface
{
    protected $listeners = ['billingStepSave' => 'refresh'];

    public bool $enableBillingAsShipping = true;
    public bool $billingAsShipping = true;

    public function __construct(
        private readonly Config $config,
        private readonly ValidatorInterface $stepValidator,
        private readonly CheckoutState $checkoutState,
        private readonly Copy $copyService
    ) {
    }

    public function boot(): void
    {
        $this->enableBillingAsShipping = $this->config->enableSameAsBilling();
        $this->billingAsShipping = (bool)$this->checkoutState->getQuote()->getBillingAddress()->getSameAsBilling();

        if (false === $this->enableBillingAsShipping) {
            $this->billingAsShipping = false;
        }
    }

    public function updated(mixed $value, string $name)
    {
        if ($name === 'billingAsShipping') {
            $value = (bool)$value;
            $quote = $this->checkoutState->getQuote();
            $billingAddress = $quote->getBillingAddress();
            $billingAddress->setSameAsBilling((int)$value);
            $quote->save();
            $this->emit('billingStepSave');
        }

        return $value;
    }

    public function copyFromShippingAddress(): void
    {
        $quote = $this->checkoutState->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $billingAddress = $quote->getBillingAddress();

        $this->copyService->copyFieldsetToTarget(
            'customer_address',
            'to_quote_address',
            $shippingAddress,
            $billingAddress
        );

        $quote->save();

        $this->emit('billingStepSave');
    }

    public function getCode(): string
    {
        return 'billing';
    }

    public function getLabel(): string
    {
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
