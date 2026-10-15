<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator\Component;

use Magento\Customer\Api\AccountManagementInterface;
use Loki\Components\Component\ComponentInterface;
use Loki\Components\Validator\ValidatorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class EmailAvailableValidator implements ValidatorInterface
{
    private const XML_PATH_ENABLE_GUEST_CHECKOUT_LOGIN = 'checkout/options/enable_guest_checkout_login';

    public function __construct(
        private readonly AccountManagementInterface $accountManagement,
        private readonly ScopeConfigInterface $scopeConfig,
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

        // Prevent customer account enumeration, also on Magento versions before 2.4.7
        if (false === $this->isGuestCheckoutLoginEnabled()) {
            return true;
        }

        if (false === $this->accountManagement->isEmailAvailable($email)) {
            return [$this->getErrorMessage($email)];
        }

        return true;
    }

    private function isGuestCheckoutLoginEnabled(): bool
    {
        return (bool)$this->scopeConfig->getValue(
            self::XML_PATH_ENABLE_GUEST_CHECKOUT_LOGIN,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    private function getErrorMessage(string $email): string
    {
        if ($this->showEmailInMessage) {
            return (string)__('The email address %1 is not available', $email);
        }

        return (string)__('The email address is not available');
    }
}
