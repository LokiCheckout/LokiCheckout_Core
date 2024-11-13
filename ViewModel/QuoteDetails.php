<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

class QuoteDetails implements ArgumentInterface
{
    public function __construct(
        private CheckoutSession $checkoutSession,
        private DateTimeFactory $dateTimeFactory
    ) {
    }

    public function getEmail(): string
    {
        return (string)$this->checkoutSession->getQuote()->getCustomerEmail();
    }

    public function getGender(): string
    {
        return (string)$this->checkoutSession->getQuote()->getCustomerGender();
    }

    public function getDob(): string
    {
        $dob =(string)$this->checkoutSession->getQuote()->getCustomerDob();
        if (empty($dob)) {
            return '';
        }

        /** @var DateTime $dateTime */
        $dateTime = $this->dateTimeFactory->create();
        return $dateTime->date('Y-m-d', strtotime($dob)); // @todo: Allow customizing this format
    }
}
