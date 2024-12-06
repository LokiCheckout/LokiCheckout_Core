<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\Behaviour;

use Magento\Framework\View\Element\AbstractBlock;

trait DomElementBehaviour
{
    use StringConversionBehaviour;

    protected string $elementId = '';

    // @todo: Rename this to getDomElementId()?
    public function getElementId(): string
    {
        $elementId = $this->elementId;
        if (empty($elementId)) {
            $parent = $this->getParent();
            if ($parent instanceof AbstractBlock) {
                $elementId = $parent->getNameInLayout();
            }
        }

        if (empty($elementId)) {
            return $this->id;
        }

        return $this->removeDashes($elementId);
    }

    // @todo: Rename this to setDomElementId()?
    public function setElementId(string $elementId)
    {
        $this->elementId = $elementId;
    }
}
