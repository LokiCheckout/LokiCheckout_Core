<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\MagewireCheckout\Magewire\AbstractComponent;

class Lastname extends AbstractComponent
{
    public string $lastname = '';

    public function mount(): void
    {
        $this->lastname = $this->checkoutState->getQuote()->getBillingAddress()->getLastname();
    }

    public function updatedLastname(string $lastname): string
    {
        $this->checkoutState->getQuote()->getBillingAddress()->setLastname($lastname);
        return $lastname;
    }
}
