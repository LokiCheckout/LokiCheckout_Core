<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\Behaviour;

use Magento\Framework\View\Element\AbstractBlock;

trait DomElementBehaviour
{
    use StringConversionBehaviour;

    protected string $elementId = '';

    public function getDomElementId(): string
    {
        $elementId = $this->elementId;
        if (empty($elementId)) {
            $parent = $this->getParent();
            if ($parent instanceof AbstractBlock) {
                $elementId = $parent->getNameInLayout();
            }
        }

        if (empty($elementId)) {
            return (string)$this->id;
        }

        $elementId = strtolower($elementId);
        return $this->removeDashes($elementId);
    }
}
