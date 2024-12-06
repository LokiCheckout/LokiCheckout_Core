<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\Behaviour;

use Magento\Framework\View\Element\AbstractBlock;

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
            'elementId' => $this->getDomElementId(),
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
