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

    public function getJsName(): string
    {
        // @todo: Use CamelCaseConvertor instead or even getParentBlock()->getJsId()
        $name = ucwords((string)$this->getNameInLayout(), '_.-');
        if (empty($name)) {
            return $this->id;
        }

        return preg_replace('#[_.-]+#', '', $name);
    }

    public function getJsValue(): string
    {
        return str_replace("\n", '\n', $this->value); // @todo: Is this good enough?
    }

    // @todo: Rename this to getDomElementId()?
    public function getElementId(): string
    {
        $elementId = !empty($this->elementId) ? $this->elementId : $this->getNameInLayout();
        if (empty($elementId)) {
            return $this->id;
        }

        return preg_replace('#([^a-zA-Z0-9]{1})#', '-', $elementId);
    }

    // @todo: Rename this to setDomElementId()?
    public function setElementId(string $elementId)
    {
        $this->elementId = $elementId;
    }

}
