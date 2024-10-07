<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Validator;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Validator\Exception;
use Magento\Quote\Model\QuoteValidator as CoreQuoteValidator;

class QuoteValidator implements ValidatorInterface
{
    public function __construct(
        private readonly CheckoutSession $checkoutSession,
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
        $quote = $this->checkoutSession->getQuote();
        $this->quoteValidator->validateBeforeSubmit($quote);

        // @todo: Double-check that this is actually throwing errors

        return true;
    }
}
