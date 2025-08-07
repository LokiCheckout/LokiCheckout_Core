<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\StepButton\StepForwardButton;

use Magento\Quote\Model\Quote;
use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use LokiCheckout\Core\Component\Base\Generic\CheckoutViewModel;
use Loki\Field\Util\ButtonInterface;

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
            'reactivateTimeout' => $this->getReactivateTimeout(),
        ];
    }

    public function getStep(): string
    {
        return 'none';
    }

    public function getButtonLabel(): string
    {
        $buttonLabelWithoutPayment = trim((string)$this->getBlock()->getButtonLabelWithoutPayment());

        /** @var Quote $quote */
        $quote = $this->getContext()->getQuote();

        if ($buttonLabelWithoutPayment && false === $quote->getGrandTotal() > 0) {
            return $buttonLabelWithoutPayment;
        }

        return $this->getBlock()->getButtonLabel();
    }

    private function getReactivateTimeout(): int
    {
        $timeout = (int)$this->getBlock()->getReactivateTimeout();
        if ($timeout > 0) {
            return $timeout;
        }

        return 10000;
    }
}
