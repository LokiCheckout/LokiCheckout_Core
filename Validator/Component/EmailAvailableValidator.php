<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Component;

use Magento\Customer\Api\AccountManagementInterface;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\Validator\ValidatorInterface;

class EmailAvailableValidator implements ValidatorInterface
{
    public function __construct(
        private readonly AccountManagementInterface $accountManagement,
    ) {
    }

    public function validate(mixed $value, ?ComponentInterface $component = null): true|array
    {
        $email = trim((string)$value);

        if ($email === '' || $email === '0') {
            return true;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['Invalid email'];
        }

        // Mental note, this only triggers if `checkout/options/enable_guest_checkout_login` is enabled
        if (false === $this->accountManagement->isEmailAvailable($email)) {
            return ['This email address is not available'];
        }

        return true;
    }
}
