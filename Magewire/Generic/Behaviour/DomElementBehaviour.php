<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Generic\Behaviour;

trait DomElementBehaviour
{
    use StringConversionBehaviour;

    protected string $elementId = '';

    // @todo: Rename this to getDomElementId()?
    public function getElementId(): string
    {
        $elementId = !empty($this->elementId) ? $this->elementId : $this->getNameInLayout();
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
