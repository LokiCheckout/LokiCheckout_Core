<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Customer\LoginForm;

use Loki\Components\Component\ComponentRepository;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Recaptcha\Config as RecaptchaConfig;
use LokiCheckout\Core\Recaptcha\FormId;
use LokiCheckout\Core\Recaptcha\Guard;
use LokiCheckout\Core\Util\LoginCustomer;
use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * @method CheckoutContext getContext()
 */
class LoginFormRepository extends ComponentRepository
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly LoginCustomer $loginCustomer,
        private readonly Guard $recaptchaGuard,
        private readonly RecaptchaConfig $recaptchaConfig,
    ) {
    }

    public function getValue(): mixed
    {
        return $this->getCustomerSession()->isLoggedIn();
    }

    public function saveValue(mixed $value): void
    {
        if (false === is_array($value)) {
            return;
        }

        if (!isset($value['email']) || !isset($value['password'])) {
            return;
        }

        if (false === $this->recaptchaGuard->assertPassed(FormId::CUSTOMER_LOGIN)) {
            $this->getLocalMessageRegistry()->addError($this->recaptchaConfig->getValidationFailureMessage());

            return;
        }

        $this->loginCustomer->execute($value['email'], $value['password']);

        // @todo: Prevent reloading all targets in case of an error
    }

    private function getCustomerSession(): CustomerSession
    {
        return $this->checkoutState->getCustomerSession();
    }
}
