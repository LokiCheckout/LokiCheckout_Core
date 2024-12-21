<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Form\Field\AddressFieldBehaviour;

use Yireo\LokiCheckout\Component\SetterInjection\InjectAddressState;
use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;

trait AddressStateBehaviour
{
    use InjectAddressState;

    /**
     * Added via \Yireo\LokiCheckout\Plugin\SetterInjectionPlugin
     *
     * @return AddressStateInterface|null
     */
    public function getAddressState(): ?AddressStateInterface
    {
        // @todo: Should this ever be returning null?
        if (false === $this->addressState instanceof AddressStateInterface) {
            throw new \RuntimeException('Empty address state: '.get_class($this));
        }

        return $this->addressState;
    }

    public function getAddressType(): string
    {
        if (strstr($this->getBlock()->getNameInLayout(), 'billing.address')) {
            return 'billing';
        }

        return 'shipping';
    }
}
