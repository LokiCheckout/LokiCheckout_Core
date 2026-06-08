<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use Loki\Components\Util\SkipValidation;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Address;

class CleanAfterAddressChange implements ObserverInterface
{
    public function __construct(
        private SkipValidation $skipValidation,
    ) {
    }

    public function execute(Observer $observer)
    {
        /** @var Address $address */
        $address = $observer->getAddress();
        $addressType = $observer->getAddressType();
        $fieldName = $observer->getFieldName();

        if ($fieldName === 'country_id') {
            $this->resetAddress($address, $addressType);
            $this->skipValidation->setSkipValidation(true);
        }
    }

    public function resetAddress(Address $address, string $addressType)
    {
        $address->setStreet('');
        $address->setPostcode('');
        $address->setCity('');
        $address->setRegion('');
        $address->setRegionId(0);

        if ($addressType === 'shipping') {
            $address->setShippingMethod('');
        }
    }
}
