<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\ProgressBar;

use Yireo\LokiCheckout\Component\Base\Generic\GenericContext;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method GenericContext getContext()
 */
class ProgressBarRepository extends ComponentRepository
{
    protected function getData(): mixed
    {
        return $this->getContext()->getStepNavigator()->getCurrentStep()->getCode();
    }

    protected function saveData(mixed $data): void
    {
        $this->getContext()->getStepNavigator()->setCurrentStepByCode($data);
    }
}
