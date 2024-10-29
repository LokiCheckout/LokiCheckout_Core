<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

use Magento\Customer\Model\Session as CustomerSession;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

class Email extends AddressFieldComponent
{
    public string $email = '';

    protected array $validators = ['email'];

    public function __construct(
        private CustomerSession $customerSession
    ) {
    }

    public function getFieldLabel(): string
    {
        return 'Email';
    }

    public function getInputType(): string
    {
        return 'email';
    }

    public function isRequired(): bool
    {
        return true;
    }

    public function isVisible(): bool
    {
        return false === $this->customerSession->isLoggedIn();
    }

    public function beforeSave($value): void
    {
        if ($this->addressState->getQuote()->getCustomerIsGuest()) {
            $this->addressState->getQuote()->setCustomerEmail($value);
        }
    }
}
