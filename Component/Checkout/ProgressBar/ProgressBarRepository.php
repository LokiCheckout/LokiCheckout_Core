<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\ProgressBar;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Loki\Components\Component\ComponentRepository;

/**
 * @method CheckoutContext getContext()
 */
class ProgressBarRepository extends ComponentRepository
{
    public function getValue(): mixed
    {
        return $this->getContext()->getStepNavigator()->getCurrentStep()->getCode();
    }

    public function saveValue(mixed $value): void
    {
        $this->getContext()->getStepNavigator()->setCurrentStepByCode((string)$value);
    }
}
