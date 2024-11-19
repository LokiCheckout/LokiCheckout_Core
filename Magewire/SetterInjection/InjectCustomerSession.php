<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\SetterInjection;


use Magento\Customer\Model\Session as CustomerSession;

trait InjectCustomerSession
{
    protected ?CustomerSession $customerSession = null;

    public function setCustomerSession(CustomerSession $customerSession)
    {
        $this->customerSession = $customerSession;
    }
}
