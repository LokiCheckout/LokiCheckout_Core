<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout;

use Yireo\MagewireCheckout\Magewire\AbstractComponent;

class Progressbar extends AbstractComponent
{
    public function goToStep(string $stepCode)
    {
        $this->checkoutState->getStepNavigator()->setCurrentStepByCode($stepCode);
        $this->emit('quoteSave', $this->checkoutState->getQuote());
    }
}
