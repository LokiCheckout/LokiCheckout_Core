<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\Behaviour;

trait AlpineDataBehaviour
{
    use DomElementBehaviour;
    use StringConversionBehaviour;

    public function getAlpineData(): array
    {
        $rules = [];
        if (method_exists($this, 'getValidators')) {
            foreach ($this->getValidators() as $validator) {
                $rules[] = $validator;
            }
        }

        $data = [
            'name' => $this->getAlpineComponentName(),
            'wireId' => $this->id,
            'elementId' => $this->getElementId(),
            'step' => $this->getStep(),
            'rules' => $rules,
        ];

        $data = array_merge($data, $this->getAdditionalAlpineData());

        if (property_exists($this, 'value')) {
            $data['value'] = $this->getJsValue();
        }

        if (property_exists($this, 'valid')) {
            $data['valid'] = $this->valid;
        }

        return $data;
    }

    public function getAdditionalAlpineData(): array
    {
        return [];
    }

    /**
     * @deprecated Use getAlpineData() instead
     */
    public function getComponentData(): array
    {
        return $this->getAlpineData();
    }

    public function getAlpineDataAsJson(): string
    {
        return json_encode($this->getAlpineData());
    }
    /**
     * @deprecated Use getAlpineDataAsJson() instead
     */
    public function getComponentDataAsJson(): string
    {
        return $this->getAlpineDataAsJson();
    }

    public function getAlpineComponentName(): string
    {
        // @todo: Use CamelCaseConvertor instead or even getParentBlock()->getJsId()
        $name = $this->getNameInLayout();
        if (empty($name)) {
            $name = $this->id;
        }

        return $this->toCamelCase($name);
    }

    /**
     * @deprecated Use getAlpineComponentName() instead
     */
    public function getJsName(): string
    {
        return $this->getAlpineComponentName();
    }

    public function getJsValue(): string
    {
        return str_replace("\n", '\n', $this->value); // @todo: Is this good enough?
    }
}
