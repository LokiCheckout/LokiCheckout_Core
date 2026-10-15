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
        if (false === $customer instanceof CustomerInterface) {
            return $proceed($customer);
        }

        $quoteCustomerId = (int)$quote->getCustomerId();
        if ($quoteCustomerId === 0 || $quoteCustomerId !== (int)$customer->getId()) {
            return $proceed($customer);
        }

        // Copy the customer data anyway when the customer group changed, so that pricing is not based on a stale group
        if ((int)$quote->getCustomerGroupId() !== (int)$customer->getGroupId()) {
            return $proceed($customer);
        }

        return $quote;
    }
}
