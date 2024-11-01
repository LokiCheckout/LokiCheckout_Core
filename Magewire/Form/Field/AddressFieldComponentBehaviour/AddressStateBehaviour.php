<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponentBehaviour;

use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;

trait AddressStateBehaviour
{
    protected ?AddressStateInterface $addressState = null;

    /**
     * Added via \Yireo\LokiCheckout\Plugin\SetterInjectionPlugin
     *
     * @return AddressStateInterface|null
     */
    public function getAddressState(): ?AddressStateInterface
    {
        return $this->addressState;
    }

    /**
     * Added via \Yireo\LokiCheckout\Plugin\SetterInjectionPlugin
     *
     * @param AddressStateInterface $addressState
     * @return void
     */
    public function setAddressState(AddressStateInterface $addressState)
    {
        $this->addressState = $addressState;
    }
}
