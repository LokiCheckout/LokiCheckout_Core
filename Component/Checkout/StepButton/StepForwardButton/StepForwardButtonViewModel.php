<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\StepButton\StepForwardButton;

use Yireo\LokiCheckout\Component\Base\Generic\CheckoutContext;
use Yireo\LokiCheckout\Component\Base\Generic\CheckoutViewModel;
use Yireo\LokiFieldComponents\Util\ButtonInterface;

/**
 * @method CheckoutContext getContext()
 */
class StepForwardButtonViewModel extends CheckoutViewModel implements ButtonInterface
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

    public function getStep(): string
    {
        return 'none';
    }

    public function getButtonLabel(): string
    {
        $buttonLabelWithoutPayment = trim((string)$this->getBlock()->getButtonLabelWithoutPayment());
        $quote = $this->getContext()->getQuote();

        if ($buttonLabelWithoutPayment && false === $quote->getGrandTotal() > 0) {
            return $buttonLabelWithoutPayment;
        }

        return $this->getBlock()->getButtonLabel();
    }
}
