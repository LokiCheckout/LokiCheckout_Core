<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator\Quote\ValidationRule;

use Magento\Framework\Validation\ValidationResultFactory;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\ValidationRules\QuoteValidationRuleInterface;

class Custom implements QuoteValidationRuleInterface
{
    public function __construct(
        private ValidationResultFactory $validationResultFactory
    ) {
    }

    public function validate(Quote $quote): array
    {
        $validationErrors = [];

        if ($quote->isVirtual()) {
            $validationErrors[] = __(
                '@todo: Supporting virtual quotes is still in progress'
            );
        }

        return [$this->validationResultFactory->create(['errors' => $validationErrors])];
    }
}
