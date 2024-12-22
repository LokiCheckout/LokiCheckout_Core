<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Component;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Component\Checkout\Step\StepInterface;
use Yireo\LokiCheckout\Component\Form\Field\FieldInterface;
use Yireo\LokiCheckout\Component\Generic\Behaviour\StringConversionBehaviour;
use Yireo\LokiCheckout\Component\GenericInterface;
use Yireo\LokiComponents\Component\ComponentInterface;
use Yireo\LokiComponents\ViewModel\ComponentUtil;

class AlpineComponentData implements ArgumentInterface
{
    use StringConversionBehaviour;

    public function __construct(
        private ComponentUtil $componentUtil
    ) {
    }

    public function getData(GenericInterface $component): array
    {
        $data = [];
        $block = $component->getBlock();

        try {
            $data['name'] = $this->getName($block);
            $data['elementId'] = $this->componentUtil->getElementIdByBlockName($block->getNameInLayout());
        } catch(\RuntimeException $e) {}

        if ($component instanceof GenericInterface) {
            $data['blockId'] = $block->getNameInLayout();
            $data['target'] = $this->getTargets($component);
        }

        if ($component instanceof FieldInterface) {
            $validators = [];
            foreach ($component->getValidators() as $validator) {
                $validators[] = $validator;
            }

            $data['validators'] = $validators;
            $data['value'] = $component->getValue();
            $data['valid'] = $component->isValid();
            $data['disabled'] = $component->isDisabled();
            $data['step'] = $component->getStep();
        }

        if ($component instanceof StepInterface) {
            $data['step'] = $component->getCode();
            $data['visible'] = $component->isVisible();
        }

        if (method_exists($component, 'getAlpineData')) {
            $data = array_merge($data, $component->getAlpineData());
        }

        return $data;
    }

    public function getJson(GenericInterface $component): string
    {
        return json_encode($this->getData($component));
    }

    public function getName(AbstractBlock $block): string
    {
        return $this->toCamelCase($block->getNameInLayout());
    }

    public function getJsValue(string $value): string
    {
        return str_replace("\n", '\n', $value); // @todo: Is this good enough?
    }

    private function getTargets(GenericInterface $component): string
    {
        $targetNames = [
            ...$component->getTargets(),
            $component->getBlock()->getNameInLayout()
        ];

        $targets = (array)$component->getBlock()->getTargets();
        foreach ($targets as $target) {
            $targetNames[] = $target;
        }

        $targetNames = $this->convertDomIds($targetNames);
        $targetNames = array_unique($targetNames);
        return implode(' ', $targetNames);
    }

    private function convertDomIds(array $domIds): array
    {
        foreach ($domIds as $domIdIndex => $domId) {
            $domIds[$domIdIndex] = $this->getDomId($domId);
        }

        return $domIds;
    }

    private function getDomId(string $id): string
    {
        return preg_replace('/([^a-z\-]+)/', '-', $id);
    }
}
