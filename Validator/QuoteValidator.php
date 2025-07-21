<?php declare(strict_types=1);

namespace LokiCheckout\Core\Validator;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validator\Exception;
use Magento\Quote\Model\QuoteValidator as CoreQuoteValidator;
use LokiCheckout\Core\ViewModel\CheckoutState;

class QuoteValidator implements ValidatorInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly CoreQuoteValidator $quoteValidator,
    ) {
    }

    /**
     * @return true|array
     * @throws Exception
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function validate(): true|array
    {
        $quote = $this->checkoutState->getQuote();
        $this->quoteValidator->validateBeforeSubmit($quote);

        // @todo: Double-check that this is actually throwing errors

        return true;
    }
}
