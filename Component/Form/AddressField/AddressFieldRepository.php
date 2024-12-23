<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\AddressField;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldBehaviour\AddressStateBehaviour;
use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;
use Yireo\LokiComponents\Component\ComponentRepository;

/**
 * @method AddressFieldContext getContext()
 */
class AddressFieldRepository extends ComponentRepository
{
    use AddressStateBehaviour;

    protected function getData(): mixed
    {
        return $this->getAddressState()->load($this->getFieldName());
    }

    protected function saveData(mixed $data): void
    {
        $this->getAddressState()->save($this->getFieldName(), $data);
    }

    protected function getAddressState(): AddressStateInterface
    {
        $blockName = $this->component->getCurrentSource()->getNameInLayout();
        if (strstr($blockName, 'billing.address')) {
            return $this->getContext()->billingAddressState;
        }

        return $this->getContext()->shippingAddressState;
    }

    protected function getFieldName(): string
    {
        $blockName = $this->component->getCurrentSource()->getNameInLayout();
        $blockNameParts = explode('.', $blockName);
        return array_pop($blockNameParts);
    }
}
