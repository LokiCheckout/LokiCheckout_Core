<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Other\Recaptcha;

use Loki\Components\Attribute\JsProperty;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Recaptcha\FormId;
use LokiCheckout\Core\Recaptcha\Type;

/**
 * @method RecaptchaContext getContext()
 * @method RecaptchaRepository getRepository()
 */
class RecaptchaViewModel extends CheckoutViewModel
{
    public function getFormId(): string
    {
        $formId = (string)$this->getBlock()->getFormId();

        return $formId !== '' ? $formId : FormId::PLACE_ORDER;
    }

    public function getCaptchaType(): string
    {
        return (string)$this->getContext()->getRecaptchaConfig()->getTypeFor($this->getFormId());
    }

    public function isAllowRendering(): bool
    {
        if (false === $this->getContext()->getRecaptchaConfig()->isEnabledFor($this->getFormId())) {
            return false;
        }

        return parent::isAllowRendering();
    }

    /**
     * The place order challenge belongs to the last step, so that it is only executed when the
     * visitor actually places the order. The other form ids are not part of step validation.
     */
    #[JsProperty(name: 'step')]
    public function getStep(): string
    {
        if ($this->getFormId() !== FormId::PLACE_ORDER) {
            return 'none';
        }

        return $this->getContext()->getStepNavigator()->getLastStep()->getCode();
    }

    public function getFieldLabel(): string
    {
        return 'reCAPTCHA';
    }

    public function showLegalNotice(): bool
    {
        return $this->getCaptchaType() === Type::V3;
    }

    /**
     * Only the v2 types are rendered as an actual widget. The v3 challenge runs without any
     * element of its own.
     */
    public function needsWidgetElement(): bool
    {
        return $this->getCaptchaType() !== Type::V3;
    }

    public function isInvisible(): bool
    {
        return Type::isInvisible($this->getCaptchaType());
    }

    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutRecaptchaComponent';
    }

    public function getJsData(): array
    {
        $captchaType = $this->getCaptchaType();

        return [
            ...parent::getJsData(),
            'formId' => $this->getFormId(),
            'captchaType' => $captchaType,
            'invisible' => $this->isInvisible(),
            'settings' => $this->getContext()->getRecaptchaConfig()->getRenderingSettings($captchaType),
            'errorText' => $this->getContext()->getRecaptchaConfig()->getValidationFailureMessage(),
        ];
    }
}
