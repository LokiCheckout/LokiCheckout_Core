<?php declare(strict_types=1);

namespace LokiCheckout\Core\CustomerData\Checkout;

use Magento\Quote\Model\Quote;

class QuoteDataCollector
{
    public function getData(Quote $quote): array
    {
        return [
            'customer_id'         => $quote->getCustomerId(),
            'customer_firstname'  => $quote->getCustomerFirstname(),
            'customer_middlename' => $quote->getCustomerMiddlename(),
            'customer_lastname'   => $quote->getCustomerLastname(),
            'customer_email'      => $quote->getCustomerEmail(),
            'customer_dob'        => $quote->getCustomerDob(),
            'customer_gender'     => $quote->getCustomerGender(),
            'currency_code'       => $quote->getQuoteCurrencyCode(),
            'grand_total'         => $quote->getGrandTotal(),
            'sub_total'           => $quote->getSubtotal(),
            'coupon_code'         => $quote->getCouponCode(),
            'payment_method'      => $quote->getPayment()->getMethod(),
            'shipping_method'     => $quote->getShippingAddress()->getShippingMethod(),
        ];
    }
}
