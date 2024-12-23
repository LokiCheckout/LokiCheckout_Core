<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Component;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Yireo\LokiCheckout\Component\Checkout\Step\StepViewModelInterface;
use Yireo\LokiCheckout\Component\Form\Field\FieldViewModelInterface;
use Yireo\LokiCheckout\Component\GenericViewModelInterface;
use Yireo\LokiCheckout\Util\CamelCaseConvertor;
use Yireo\LokiCheckout\Util\IdConvertor;
use Yireo\LokiComponents\ViewModel\ComponentUtil;

// @todo: Retrieve targets from Component-class instead of ViewModel-class
class AlpineComponentData implements ArgumentInterface
{
    public function __construct(
        private ComponentUtil      $componentUtil,
        private IdConvertor        $idConvertor,
        private CamelCaseConvertor $camelCaseConvertor
    ) {
    }

    public function getData(GenericViewModelInterface $viewModel): array
    {
        $data = [];
        $block = $viewModel->getBlock();

        try {
            $data['name'] = $this->getName($block);
            $data['elementId'] = $this->componentUtil->getElementIdByBlockName($block->getNameInLayout());
        } catch (\RuntimeException $e) {
        }

        if ($viewModel instanceof GenericViewModelInterface) {
            $data['blockId'] = $block->getNameInLayout();
            $data['target'] = $this->getTargets($viewModel);
        }

        if ($viewModel instanceof FieldViewModelInterface) {
            $validators = [];
            foreach ($viewModel->getValidators() as $validator) {
                $validators[] = $validator;
            }

            $data['validators'] = $validators;

            $filters = [];
            foreach ($viewModel->getFilters() as $filter) {
                $filters[] = $filter;
            }

            $data['filter'] = $filters;


            $data['value'] = $viewModel->getValue();
            $data['valid'] = $viewModel->isValid();
            $data['disabled'] = $viewModel->isDisabled();
            $data['step'] = $viewModel->getStep();
        }

        if ($viewModel instanceof StepViewModelInterface) {
            $data['step'] = $viewModel->getCode();
            $data['visible'] = $viewModel->isVisible();
        }

        if (method_exists($viewModel, 'getAlpineData')) {
            $data = array_merge($data, $viewModel->getAlpineData());
        }

        return $data;
    }

    public function getJson(GenericViewModelInterface $viewModel): string
    {
        return json_encode($this->getData($viewModel));
    }

    public function getName(AbstractBlock $block): string
    {
        return $this->camelCaseConvertor->toCamelCase($block->getNameInLayout());
    }

    public function getJsValue(string $value): string
    {
        return str_replace("\n", '\n', $value); // @todo: Is this good enough?
    }

    private function getTargets(GenericViewModelInterface $viewModel): string
    {
        $targetNames = [
            ...$viewModel->getTargets(),
            $viewModel->getBlock()->getNameInLayout()
        ];

        $targets = (array)$viewModel->getBlock()->getTargets();
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
            $domIds[$domIdIndex] = $this->idConvertor->toElementId($domId);
        }

        return $domIds;
    }
}
