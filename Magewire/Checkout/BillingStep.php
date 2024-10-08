<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\Config\Config;
use Yireo\FriggaCheckout\Step\StepInterface;
use Yireo\FriggaCheckout\Validator\ValidatorInterface;
use Yireo\FriggaCheckout\ViewModel\CheckoutState;

class BillingStep extends Component implements StepInterface
{
    public bool $enableBillingAsShipping = true;
    public bool $billingAsShipping = true;

    public function __construct(
        private readonly Config $config,
        private readonly ValidatorInterface $stepValidator,
        private readonly CheckoutState $checkoutState
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
            $billingAddress = $this->checkoutState->getQuote()->getBillingAddress();
            $billingAddress->setSameAsBilling((int)$value);
            $this->checkoutState->getQuote()->save();
            $this->emit('quoteSave');
        }

        return $value;
    }

    public function copyFromShippingAddress(): void
    {
        $quote = $this->checkoutState->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $billingAddress = $quote->getBillingAddress();
        $billingAddress->addData($shippingAddress->getData());
        $quote->save();

        $this->emit('quoteSave');
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
