<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator\Component;

use Magento\Customer\Api\AccountManagementInterface;
use Loki\Components\Component\ComponentInterface;
use Loki\Components\Validator\ValidatorInterface;

class EmailAvailableValidator implements ValidatorInterface
{
    public function __construct(
        private readonly AccountManagementInterface $accountManagement,
        private readonly bool $showEmailInMessage = false
    ) {
    }

    public function validate(mixed $value, ?ComponentInterface $component = null): bool|array
    {
        $email = trim((string)$value, " \f\n\r\t\v\x00");

        if ($email === '' || $email === '0') {
            return true;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['Invalid email address'];
        }

        // Mental note, this only triggers if `checkout/options/enable_guest_checkout_login` is enabled
        if (false === $this->accountManagement->isEmailAvailable($email)) {
            return [$this->getErrorMessage($email)];
        }

        return true;
    }

    private function getErrorMessage(string $email): string
    {
        if ($this->showEmailInMessage) {
            return (string)__('The email address %1 is not available', $email);
        }

        return (string)__('The email address is not available');
    }
}
