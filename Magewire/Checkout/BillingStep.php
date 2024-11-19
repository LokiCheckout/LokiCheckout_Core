<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\Step\StepInterface;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingStep extends Component implements StepInterface
{
    protected $listeners = ['billingStepSave' => 'refresh'];

    public string $refreshToken = '';

    public function __construct(
        private readonly ValidatorInterface $stepValidator,
        private readonly BillingAddressState $billingAddressState,
    ) {
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
