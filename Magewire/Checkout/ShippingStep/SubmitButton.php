<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\ShippingStep;

use Yireo\MagewireCheckout\Magewire\AbstractComponent;
use Yireo\MagewireCheckout\Magewire\Checkout\StepButtonInterface;

class SubmitButton extends AbstractComponent implements StepButtonInterface
{
    public function getLabel(): string
    {
        return 'Submit';
    }

    public function proceedToNextAction()
    {
        $this->checkoutState->getStepNavigator()->setCurrentStepByCode('billing');
        $this->emit('quoteSave', $this->checkoutState->getQuote());
    }

    public function allowAction(): bool
    {
        return true;
        $this->checkoutState->getStepNavigator()->hasCompletedCurrentStep();
        return false;
    }
}
