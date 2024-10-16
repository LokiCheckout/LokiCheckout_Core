<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Validator;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validation\ValidationResult;
use Magento\Quote\Model\ValidationRules\QuoteValidationRuleInterface;

class StepValidator implements ValidatorInterface
{
    public function __construct(
        protected readonly CheckoutSession $checkoutSession,
        protected readonly array $validationRules = [],
    ) {
    }

    /**
     * @return true|array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function validate(): array|true
    {
        $quote = $this->checkoutSession->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $shippingMethod = $shippingAddress->getShippingMethod();
        if (empty($shippingMethod)) {
            return ['No shipping method found.'];
        }

        $errors = [];
        if (empty($this->validationRules)) {
            $errors[] = 'No validators found';
        }

        foreach ($this->validationRules as $validationRule) {
            if (false === $validationRule instanceof QuoteValidationRuleInterface) {
                continue;
            }

            $validationResults = $validationRule->validate($quote);
            if (empty($validationResults)) {
                continue;
            }

            foreach ($validationResults as $validationResult) {
                $errors = array_merge($errors, $validationResult->getErrors());
            }
        }

        if (empty($errors)) {
            return true;
        }

        return $errors;
    }
}
