<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig\Value;

use Yireo\LokiCheckout\Config\XmlConfig\Value\Theme\Step;
use Yireo\LokiCheckout\Exception\UnknownStepException;
use Yireo\LokiCheckout\Exception\StepNotFoundException;

class Theme
{
    public function __construct(
        private readonly string $name,
        private readonly string $label,
        private readonly array $steps = []
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return Step[]
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * @param string $stepName
     * @return Step
     * @throws UnknownStepException
     */
    public function getStep(string $stepName): Step
    {
        foreach ($this->getSteps() as $step) {
            if ($step->getName() === $stepName) {
                return $step;
            }
        }

        throw new StepNotFoundException((string)__('Step with name "%1" not found', $stepName));
    }
}
