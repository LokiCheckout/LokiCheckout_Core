<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Recaptcha;

use Loki\Components\Component\AbstractComponentContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Recaptcha\Config;
use LokiCheckout\Core\Recaptcha\Guard;
use LokiCheckout\Core\Recaptcha\TokenValidatorInterface;

/**
 * @method Config getRecaptchaConfig()
 * @method Guard getRecaptchaGuard()
 * @method TokenValidatorInterface getTokenValidator()
 * @mixin CheckoutContext
 */
class RecaptchaContext extends AbstractComponentContext
{
}
