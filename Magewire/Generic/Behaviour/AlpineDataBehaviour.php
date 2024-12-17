<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\Behaviour;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Magewire\Form\Field\FieldComponent;
use Yireo\LokiCheckout\Magewire\Generic\GenericComponent;

trait AlpineDataBehaviour
{
    use DomElementBehaviour;
    use StringConversionBehaviour;

    public function getAlpineData(): array
    {
        $data = [];

        if ($this instanceof GenericComponent) {
            $data = [
                ...$data,
                'name' => $this->getAlpineComponentName(),
                'wireId' => $this->id,
                'elementId' => $this->getDomElementId(),
                'step' => $this->getStep(),
                'visible' => $this->visible,
                'lazyLoad' => $this->lazyLoad,
            ];
        }

        if ($this instanceof FieldComponent) {
            $validators = [];
            if (method_exists($this, 'getValidators')) {
                foreach ($this->getValidators() as $validator) {
                    $validators[] = $validator;
                }
            }

            $data['validators'] = $validators;
            $data['value'] = $this->getJsValue();
            $data['valid'] = $this->valid;
        }

        return array_merge($data, $this->getAdditionalAlpineData());
    }

    public function getAdditionalAlpineData(): array
    {
        return [];
    }

    public function getAlpineDataAsJson(): string
    {
        return json_encode($this->getAlpineData());
    }

    public function getAlpineComponentName(): string
    {
        $name = '';

        $parent = $this->getParent();
        if ($parent instanceof AbstractBlock) {
            $name = $parent->getNameInLayout();
        }

        if (empty($name)) {
            $name = (string)$this->id;
        }

        return $this->toCamelCase($name);
    }

    public function getJsValue(): string
    {
        return str_replace("\n", '\n', $this->value); // @todo: Is this good enough?
    }
}
