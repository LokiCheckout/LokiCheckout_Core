<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Stdlib\DateTime\DateTimeFactory;

class QuoteDetails implements ArgumentInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly DateTimeFactory $dateTimeFactory
    ) {
    }

    public function getEmail(): string
    {
        return (string)$this->checkoutState->getQuote()->getCustomerEmail();
    }

    public function getGender(): string
    {
        return (string)$this->checkoutState->getQuote()->getCustomerGender();
    }

    public function getDob(): string
    {
        $dob =(string)$this->checkoutState->getQuote()->getCustomerDob();
        if ($dob === '' || $dob === '0') {
            return '';
        }

        /** @var DateTime $dateTime */
        $dateTime = $this->dateTimeFactory->create();
        return $dateTime->date('Y-m-d', strtotime($dob)); // @todo: Allow customizing this format
    }
}
