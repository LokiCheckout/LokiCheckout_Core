<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\SaveInAddressBook;

use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldViewModel;

class SaveInAddressBookViewModel extends AddressFieldViewModel
{
    public function isAllowRendering(): bool
    {
        if (false === $this->getContext()->getCheckoutState()->getCustomerSession()->isLoggedIn()) {
            return false;
        }

        return parent::isAllowRendering();
    }

    public function getInputLabel(): string
    {
        return 'Save in address book';
    }

    public function getValue(): mixed
    {
        return (bool)parent::getValue();
    }
}
