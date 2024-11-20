<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Checkout\Sidebar\Preview;

use Magento\Customer\Block\Address\Renderer\RendererInterface;
use Magento\Customer\Model\Address\Config as AddressConfig;
use Magento\Customer\Model\Address\Mapper as AddressMapper;
use Magewirephp\Magewire\Component;
use Yireo\LokiCheckout\ViewModel\State\BillingAddressState;

class BillingAddress extends Component
{
    protected $listeners = [
        'billingStepSave' => 'refresh'
    ];

    public function __construct(
        private readonly AddressConfig $addressConfig,
        private readonly BillingAddressState $billingAddressState,
        private readonly AddressMapper $addressMapper
    ) {
    }

    public function isSameAsShipping(): bool
    {
        return (bool)$this->billingAddressState->getBillingAddress()->getSameAsBilling();
    }

    public function getHtml(): string
    {
        $address = $this->billingAddressState->getBillingAddress();
        $address = $address->getDataModel(0, 0);
        $addressData = $this->addressMapper->toFlatArray($address);
        if ($this->isEmpty($addressData)) {
            return '';
        }

        /** @var RendererInterface $renderer */
        $renderer = $this->addressConfig->getFormatByCode('html')->getRenderer();
        return $renderer->renderArray($addressData);
    }

    private function isEmpty(array $addressData): bool
    {
        $isEmpty = true;
        foreach ($addressData as $fieldName => $fieldValue) {
            if (in_array($fieldName, ['id', 'country_id', 'vat_id'])) {
                continue;
            }

            if (empty($fieldValue)) {
                continue;
            }

            $isEmpty = false;
        }

        return $isEmpty;
    }
}
