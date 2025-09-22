<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Customer\LoginForm;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Loki\Components\Component\ComponentViewModel;

/**
 * @method CheckoutContext getContext()
 */
class LoginFormViewModel extends ComponentViewModel
{
    public function getJsComponentName(): string
    {
        return 'LokiCheckoutLoginComponent';
    }
}
