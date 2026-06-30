<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\Behaviour;

use Loki\Components\Attribute\JsProperty;
use Magento\Framework\View\Element\AbstractBlock;

/**
 * @method AbstractBlock getBlock()
 */
trait RequiredViewModelBehaviour
{
    protected bool $isRequiredByDefault = false;

    #[JsProperty(name: 'required')]
    public function isRequired(): bool
    {
        $required = $this->getBlock()->getRequired();
        if (is_bool($required)) {
            return $required;
        }

        return $this->isRequiredByDefault;
    }
}
