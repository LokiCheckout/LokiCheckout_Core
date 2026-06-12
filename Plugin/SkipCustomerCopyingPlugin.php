<?php declare(strict_types=1);

namespace LokiCheckout\Core\Plugin;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Quote\Model\Quote;

class SkipCustomerCopyingPlugin
{
    public function aroundSetCustomer(
        Quote $quote,
        callable $proceed,
        ?CustomerInterface $customer = null
    ): Quote {
        if ($customer instanceof CustomerInterface &&
            $quote->getCustomerId() > 0 &&
            $quote->getCustomerId() === $customer->getId()) {
            return $quote;
        }

        return $proceed($customer);
    }
}
