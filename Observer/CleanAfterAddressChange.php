<?php declare(strict_types=1);

namespace LokiCheckout\Core\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Address;

class CleanAfterAddressChange implements ObserverInterface
{
    public function execute(Observer $observer)
    {
        /** @var Address $address */
        $address = $observer->getAddress();
        $addressType = $observer->getAddressType();
        $fieldName = $observer->getFieldName();

        if ($fieldName === 'country_id') {
            $address->setStreet('');
            $address->setPostcode('');
            $address->setCity('');
            $address->setRegion('');
            $address->setRegionId('');

            if ($addressType === 'shipping') {
                $address->setShippingMethod('');
            }
        }
    }
}
