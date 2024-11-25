<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire;

use Magewirephp\Magewire\Component;
use RuntimeException;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;

class ComponentRegistry
{
    private array $components;

    public function __construct()
    {
    }

    public function register(Component $component)
    {
        $this->components[get_class($component)] = $component;
    }

    /**
     * @param string $componentName
     * @return Component
     * @throws RuntimeException
     */
    public function getComponentById(string $componentName): Component
    {
        foreach ($this->getComponents() as $component) {
            if ($component->id === $componentName) {
                return $component;
            }
        }

        // @todo: Custom exception
        throw new RuntimeException("Component with name '{$componentName}' not found");
    }

    public function getComponents(): array
    {
        return $this->components;
    }

    public function getComponentsByStep(string $step): array
    {
        return array_filter($this->getComponents(), function ($component) use ($step) {
            if (false === $component instanceof FieldComponent) {
                return false;
            }

            return $component->getStep() === $step;
        });
    }
}
