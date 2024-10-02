<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Magewire\Checkout\BillingStep\BillingAddress;

use Yireo\MagewireCheckout\Magewire\AbstractComponent;

class Firstname extends AbstractComponent
{
    public string $firstname = '';

    public function mount(): void
    {
        $this->firstname = $this->checkoutState->getQuote()->getBillingAddress()->getFirstname();
    }

    public function updatedFirstname(string $firstname): string
    {
        $this->checkoutState->getQuote()->getBillingAddress()->setFirstname($firstname);
        return $firstname;
    }
}
