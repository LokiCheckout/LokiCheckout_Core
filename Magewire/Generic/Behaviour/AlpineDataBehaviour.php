<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\Behaviour;

trait AlpineDataBehaviour
{
    public function getComponentData(): array
    {
        $rules = [];
        if (method_exists($this, 'getValidators')) {
            foreach ($this->getValidators() as $validator) {
                $rules[] = $validator;
            }
        }

        return [
            'name' => $this->id,
            'wireId' => $this->id,
            'elementId' => $this->getElementId(),
            'step' => $this->getStep(),
            'value' => $this->getJsValue(),
            'valid' => $this->isValid(),
            'rules' => $rules,
        ];
    }

    public function getComponentDataAsJson(): string
    {
        return json_encode($this->getComponentData());
    }

    public function getJsValue(): string
    {
        return str_replace("\n", '\n', $this->value); // @todo: Is this good enough?
    }
}
