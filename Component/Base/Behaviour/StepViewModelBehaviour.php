<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\Behaviour;

use Magento\Framework\View\Element\AbstractBlock;

/**
 * @method AbstractBlock getBlock()
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

        $this->step = $this->getContext()->getStepProvider()->getStep($this->getBlock());
        return $this->step;
    }

    public function getJsData(): array
    {
        return [
            ...parent::getJsData(),
            'step' => $this->getStep(),
        ];
    }
}
