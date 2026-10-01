<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\StepButton\StepForwardButton;

use LokiCheckout\Core\Component\Checkout\StepButton\AbstractStepButtonViewModel;
use Magento\Quote\Model\Quote;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;

/**
 * @method CheckoutContext getContext()
 */
class StepForwardButtonViewModel extends AbstractStepButtonViewModel
{
    public function getJsComponentName(): ?string
    {
        return 'LokiCheckoutStepForwardButton';
    }

    public function getJsData(): array
    {
        return [
            'currentStep' => $this->getContext()->getStepNavigator()->getCurrentStep()->getCode(),
            'redirectUrl' => $this->getContext()->getCheckoutState()->getCheckoutSession()->getLokiRedirectUrl(),
        ];
    }

    public function getButtonLabel(): string
    {
        $buttonLabelWithoutPayment = trim((string)$this->getBlock()->getButtonLabelWithoutPayment(), " \f\n\r\t\v\x00");

        /** @var Quote $quote */
        $quote = $this->getContext()->getQuote();

        if ($buttonLabelWithoutPayment && false === $quote->getGrandTotal() > 0) {
            return $buttonLabelWithoutPayment;
        }

        return parent::getButtonLabel();
    }
}
