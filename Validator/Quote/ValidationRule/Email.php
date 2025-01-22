<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Quote\ValidationRule;

use Magento\Framework\Validation\ValidationResultFactory;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\ValidationRules\QuoteValidationRuleInterface;
use Yireo\LokiComponents\Component\ComponentRegistry;

class Email implements QuoteValidationRuleInterface
{
    public function __construct(
        private readonly ValidationResultFactory $validationResultFactory,
        private readonly ComponentRegistry $componentRegistry,
    ) {
    }

    public function validate(Quote $quote): array
    {
        $validationErrors = [];

        $email = $quote->getCustomerEmail();
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validationErrors[] = __(
                'Email address is invalid'
            );
        }

        return [$this->validationResultFactory->create(['errors' => $validationErrors])];
    }
}
