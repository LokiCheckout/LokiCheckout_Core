<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\StepButton;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use LokiCheckout\Core\Exception\UnknownStepException;
use LokiCheckout\Core\Step\StepNavigator;
use Loki\Components\Attribute\JsProperty;
use Loki\Field\Util\ButtonInterface;

/**
 * @method CheckoutContext getContext()
 */
class AbstractStepButtonViewModel extends CheckoutViewModel implements ButtonInterface
{
    public function isAllowRendering(): bool
    {
        $stepCode = $this->getBlock()->getStepCode();

        if (!empty($stepCode)) {
            try {
                $step = $this->getContext()->getStepNavigator()->getStepByCode($stepCode);
                if (false === $this->getContext()->getStepNavigator()->isCurrentStep($step)) {
                    return false;
                }
            } catch (UnknownStepException $exception) {
                return false;
            }
        }

        return parent::isAllowRendering();
    }

    protected function getStepNavigator(): StepNavigator
    {
        return $this->getContext()->getStepNavigator();
    }

    #[JsProperty(name: 'step')]
    public function getStep(): string
    {
        return 'none';
    }

    public function getButtonLabel(): string
    {
        return $this->getBlock()->getButtonLabel();
    }
}
