<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Base\AddressField;

use LokiCheckout\Core\Util\Component\AddressStateProvider;
use LokiCheckout\Core\Util\SameAddressService;
use LokiCheckout\Core\ViewModel\State\AddressStateInterface;
use Loki\Field\Component\Base\Field\FieldRepository;
use LokiCheckout\Core\ViewModel\State\ShippingAddressState;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Quote\Model\Quote\Address;

/**
 * @method AddressFieldContext getContext()
 */
class AddressFieldRepository extends FieldRepository
{
    public function __construct(
        protected AddressStateProvider $addressStateProvider,
        protected SameAddressService $sameAddressService,
        protected EventManager $eventManager
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
        return $this->getAddressState()->get($this->getFieldName());
    }

    public function saveValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        $previousValue = $this->getAddressState()->get($this->getFieldName());
        if ($previousValue === $value) {
            return;
        }

        /** @var Address $address */
        $address = $this->getAddressState()->getAddress();
        $address->setData($this->getFieldName(), (string)$value);

        $quote = $this->getContext()->getCheckoutState()->getQuote();
        $this->eventManager->dispatch('loki_checkout_address_field_set_after', [
            'field_name' => $this->getFieldName(),
            'value' => $value,
            'address' => $address,
            'address_type' => $this->getAddressState() instanceof ShippingAddressState ? 'shipping' : 'billing',
            'quote' => $quote,
        ]);

        //if ($this->sameAddressService->isSame()) {
        //    $this->sameAddressService->copyAddressToAddress($address);
        //}
    }

    protected function getAddressState(): AddressStateInterface
    {
        return $this->addressStateProvider->getAddressState($this->getComponentName());
    }
}
