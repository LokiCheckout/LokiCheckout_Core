<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\SetterInjection;

use Yireo\LokiCheckout\ViewModel\State\AddressStateInterface;

trait InjectAddressState
{
    protected ?AddressStateInterface $addressState = null;

    public function setAddressState(AddressStateInterface $addressState)
    {
        $this->addressState = $addressState;
    }
}
