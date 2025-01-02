<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Base\AddressField;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiCheckout\Component\Base\Field\FieldContext;
use Yireo\LokiCheckout\Util\Component\AddressStateProvider;
use Yireo\LokiCheckout\Util\SameAddressService;
use Yireo\LokiComponents\Component\ComponentInterface;

class AddressFieldContext extends FieldContext
{
    public function getAddressStateProvider(): AddressStateProvider
    {
        return $this->get(AddressStateProvider::class);
    }

    public function getAddressState(string $blockName)
    {
        return $this->getAddressStateProvider()->getAddressState($blockName);
    }

    public function getSameAddressService(): SameAddressService
    {
        return $this->get(SameAddressService::class);
    }
}
