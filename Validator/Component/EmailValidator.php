<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Yireo\LokiCheckout\Component\Form\Field\Field;

class EmailValidator implements FieldValidatorInterface
{
    public function __construct(
        private AccountManagementInterface $accountManagement,
    ) {
    }

    public function validate(Field $component): bool
    {
        $email = trim($component->getValue());

        if (empty($email)) {
            $component->getErrorContainer()->addError('This value is required');

            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $component->getErrorContainer()->addError( 'Invalid email');

            return false;
        }

        $parts = explode('@', $email);
        $domain = $parts[1];
        if (false === checkdnsrr($domain)) {
            $message = (string)__('Domain "%s" does not seem to be valid');
            $component->getErrorContainer()->addError( sprintf($message, $domain));
            return false;
        }

        // Mental note, this only triggers if `checkout/options/enable_guest_checkout_login` is enabled
        if (false === $this->accountManagement->isEmailAvailable($email)) {
            $component->getErrorContainer()->addError( 'This email address is not available');

            return false;
        }

        return true;
    }
}
