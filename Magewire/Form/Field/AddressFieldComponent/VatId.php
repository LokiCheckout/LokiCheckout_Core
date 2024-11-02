<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;

use Magento\Customer\Model\Vat as VatModel;
use Yireo\LokiCheckout\Magewire\Form\Field\AddressFieldComponent;
use Yireo\LokiCheckout\Util\CountryIdVatPrefixMapping;

class VatId extends AddressFieldComponent
{
    protected $listeners = ['shippingStepSave' => 'refresh'];
    protected array $filters = ['vat_id'];
}
