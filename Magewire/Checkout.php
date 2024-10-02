<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire;

class Checkout extends AbstractComponent
{
    public function getCurrentStep(): string
    {
        return $this->checkoutState->getStepNavigator()->getCurrentStep()->getCode();
    }
}
