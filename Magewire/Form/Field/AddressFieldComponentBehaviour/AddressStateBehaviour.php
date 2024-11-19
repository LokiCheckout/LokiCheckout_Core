<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour;

use Yireo\LokiCheckout\Magewire\SetterInjection\InjectAddressState;
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
        return $this->addressState;
    }
}
