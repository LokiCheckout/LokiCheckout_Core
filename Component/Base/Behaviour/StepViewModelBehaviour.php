<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Behaviour;

use Exception;
use Loki\Components\Component\AbstractComponentContext;
use Loki\Components\Component\ComponentContextInterface;

/**
 * @method ComponentContextInterface getContext()
 */
trait StepViewModelBehaviour
{
    protected string $step = '';

    public function setStep(string $step): void
    {
        $this->step = $step;
    }

    public function getStep(): string
    {
        if (!empty($this->step)) {
            return $this->step;
        }

        $context = $this->getContext();
        if (false === $this->hasStepProviderMethod($context)) {
            $message = __(
                'Component context "%1" does not have getStepProvider() method: %2',
                get_class($context),
                $this->getComponentName()
            );
            throw new Exception((string)$message);
        }

        $this->step = $this->getContext()->getStepProvider()->getStep($this->getBlock());
        return $this->step;
    }

    private function hasStepProviderMethod(ComponentContextInterface $componentContext): bool
    {
        if (method_exists($componentContext, 'getStepProvider')) {
            return true;
        }

        if ($componentContext instanceof AbstractComponentContext
            && $componentContext->hasMethod('getStepProvider')) {
            return true;
        }

            return false;
    }
}
