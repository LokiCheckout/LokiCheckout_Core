<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\AddressField;

use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method AddressFieldContext getContext()
 */
class AddressFieldRepository extends ComponentRepository
{
    public function getFieldName(): string
    {
        $blockName = $this->getComponentName();
        $blockNameParts = explode('.', $blockName);
        return array_pop($blockNameParts);
    }

    public function getValue(): mixed
    {
        return $this->getAddressState()->load($this->getFieldName());
    }

    public function saveValue(mixed $data): void
    {
        $this->getAddressState()->save($this->getFieldName(), $data);
    }

    protected function getAddressState(): AddressStateInterface
    {
        return $this->getContext()->getAddressState($this->getComponentName());
    }
}
