<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\MagewireCheckout\Config\Config;
use Yireo\MagewireCheckout\Step\StepInterface;
use Yireo\MagewireCheckout\Validator\ValidatorInterface;

class BillingStep extends Component implements StepInterface
{
    public bool $useBillingAsShipping = true;

    public function __construct(
        private readonly Config $config,
        private readonly ValidatorInterface $stepValidator,
    ) {
    }

    public function booted(): void
    {
        $this->useBillingAsShipping = $this->config->useSameAsBilling();
    }

    public function getCode(): string
    {
        return 'billing';
    }

    public function getLabel(): string
    {
        return 'Billing';
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
