<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\ValidationRules\QuoteValidationRuleInterface;
use LokiCheckout\Core\ViewModel\CheckoutState;

class StepValidator implements ValidatorInterface
{
    public function __construct(
        protected readonly CheckoutState $checkoutState,
        protected readonly array $validationRules = [],
    ) {
    }

    /**
     * @return bool|array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function validate(): bool|array
    {
        $quote = $this->checkoutState->getQuote();
        $shippingAddress = $quote->getShippingAddress();
        $shippingMethod = $shippingAddress->getShippingMethod();
        if (false === $quote->isVirtual() && empty($shippingMethod)) {
            return ['No shipping method found.'];
        }

        $errors = [];
        if ($this->validationRules === []) {
            $errors[] = 'No validators found';
        }

        foreach ($this->validationRules as $validationRule) {
            if (false === $validationRule instanceof QuoteValidationRuleInterface) {
                continue;
            }

            $validationResults = $validationRule->validate($quote);
            if ($validationResults === []) {
                continue;
            }

            foreach ($validationResults as $validationResult) {
                $errors = array_merge($errors, $validationResult->getErrors());
            }
        }

        if ($errors === []) {
            return true;
        }

        return $errors;
    }
}
