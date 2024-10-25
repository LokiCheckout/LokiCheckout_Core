<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Customer\Api\AccountManagementInterface;
use Yireo\LokiCheckout\Magewire\Generic\AddressFieldComponent;

class EmailValidator implements AddressFieldValidatorInterface
{
    public function __construct(
        private AccountManagementInterface $accountManagement,
    ) {
    }

    public function validate(AddressFieldComponent $component): bool
    {
        $email = $component->getValue();

        if (empty($email)) {
            $component->error('value', 'This value is required');

            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $component->error('value', 'Invalid email');

            return false;
        }

        $parts = explode('@', $email);
        $domain = $parts[1];
        if (false === checkdnsrr($domain)) {
            $component->error('value', (string)__('Domain "%s" does not seem to be valid', $domain));
            return false;
        }

        // Mental note, this only triggers if `checkout/options/enable_guest_checkout_login` is enabled
        if (false === $this->accountManagement->isEmailAvailable($email)) {
            $component->error('value', 'This email address is not available');

            return false;
        }

        return true;
    }
}
