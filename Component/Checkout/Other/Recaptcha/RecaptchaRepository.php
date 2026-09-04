<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Recaptcha;

use Loki\Components\Component\ComponentRepository;

/**
 * @method RecaptchaContext getContext()
 * @method RecaptchaViewModel getViewModel()
 */
class RecaptchaRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        return null;
    }

    public function saveValue(mixed $value): void
    {
        $formId = $this->getViewModel()->getFormId();
        if (false === $this->getContext()->getRecaptchaConfig()->isEnabledFor($formId)) {
            return;
        }

        $result = $this->getContext()->getTokenValidator()->validate((string)$value, $formId);
        if (false === $result->isValid()) {
            $this->getLocalMessageRegistry()->addError($result->getMessage());

            return;
        }

        $this->getContext()->getRecaptchaGuard()->markPassed($formId);
    }
}
