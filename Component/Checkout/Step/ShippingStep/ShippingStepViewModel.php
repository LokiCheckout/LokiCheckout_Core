<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Step\ShippingStep;

use LokiCheckout\Core\Component\Checkout\Step\AbstractStepViewModel;
use Magento\Framework\View\Element\AbstractBlock;

/**
 * @method ShippingStepContext getContext()
 */
class ShippingStepViewModel extends AbstractStepViewModel
{
    public function getCode(): string
    {
        return 'shipping';
    }

    public function getLabel(): string
    {
        $titleBlock = $this->getBlock()->getLayout()->getBlock('loki-checkout.steps.shipping.title');

        if ($titleBlock instanceof AbstractBlock) {
            $title = $titleBlock->getTitle();
            if (!empty($title)) {
                return $title;
            }
        }

        $title = $this->getBlock()->getTitle();
        if (!empty($title)) {
            return $title;
        }

        return 'Shipping';
    }

    public function isEnabled(): bool
    {
        return !(bool)$this->getContext()->getCheckoutState()->getQuote()->isVirtual();
    }

    public function validate(): bool|array
    {
        $result = $this->getContext()->getStepValidator()->validate();
        $this->valid = true === $result;

        return $result;
    }
}
