<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\AddressField;

use Yireo\LokiCheckout\Component\Base\Field\FieldRepository;
use Yireo\LokiCheckout\Util\Component\AddressStateProvider;
use Yireo\LokiCheckout\Util\SameAddressService;
use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;

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
        $this->getAddressState()->save($this->getFieldName(), $value);
    }

    protected function getAddressState(): AddressStateInterface
    {
        return $this->addressStateProvider->getAddressState($this->getComponentName());
    }
}
