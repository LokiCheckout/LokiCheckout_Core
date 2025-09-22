<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Step\OneStep;

use LokiCheckout\Core\Component\Checkout\Step\AbstractStepViewModel;

/**
 * @method OneStepContext getContext()
 */
class OneStepViewModel extends AbstractStepViewModel
{
    public function getCode(): string
    {
        return 'one';
    }

    public function getLabel(): string
    {
        return 'One Step';
    }

    public function validate(): bool|array
    {
        $result = $this->getContext()->getStepValidator()->validate();
        $this->valid = true === $result;

        return $result;
    }
}
