<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\AddressField;

use LokiCheckout\Core\Util\Component\AddressStateProvider;
use LokiCheckout\Core\Util\SameAddressService;
use LokiCheckout\Core\ViewModel\State\AddressStateInterface;
use Loki\Field\Component\Base\Field\FieldRepository;

/**
 * @method AddressFieldContext getContext()
 */
class AddressFieldRepository extends FieldRepository
{
    public function __construct(
        protected AddressStateProvider $addressStateProvider,
        protected SameAddressService $sameAddressService
    ) {
    }

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

    public function saveValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        $this->getAddressState()->save($this->getFieldName(), $value);
    }

    protected function getAddressState(): AddressStateInterface
    {
        return $this->addressStateProvider->getAddressState($this->getComponentName());
    }
}
