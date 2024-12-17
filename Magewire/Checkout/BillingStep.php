<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout;

use Yireo\LokiCheckout\Magewire\Generic\Behaviour\AlpineDataBehaviour;
use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;
use Yireo\LokiCheckout\Step\StepComponentInterface;
use Yireo\LokiCheckout\Step\StepNavigator;
use Yireo\LokiCheckout\Validator\ValidatorInterface;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingStep extends GenericComponent implements StepComponentInterface
{
    use AlpineDataBehaviour;

    protected $listeners = [
        //'afterChangeStep' => 'refresh',
        'afterSaveBillingStep' => 'refresh',
    ];

    public string $refreshToken = ''; // @todo: What is this again?

    public function __construct(
        private readonly ValidatorInterface $stepValidator,
        private readonly BillingAddressState $billingAddressState,
        private readonly StepNavigator $stepNavigator
    ) {
    }

    public function boot(): void
    {
        if (false === $this->stepNavigator->isCurrentStep($this)) {
            $this->visible = false;
        }

        // @todo: Double-check for free products
        //if ((bool)$this->checkoutState->getQuote()->isVirtual()) {
        //    $this->allowRendering = false;
        //}

        parent::boot();
    }

    public function getAdditionalAlpineData(): array
    {
        return [
            'currentStep' => $this->getCode(),
        ];
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
        // @todo: Move this logic to step navigator itself
        if ($this->stepNavigator->isCurrentStep($this)) {
            return true;
        }

        return false;
    }

    public function validate(): true|array
    {
        return $this->stepValidator->validate();
    }
}
