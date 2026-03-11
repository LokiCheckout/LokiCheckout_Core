<?php declare(strict_types=1);

namespace LokiCheckout\Core\Component\Checkout\Sidebar\CouponCode;

use LokiCheckout\Core\Component\Base\Generic\CheckoutContext;
use Loki\Field\Component\Base\Field\FieldRepository;

/**
 * @method CheckoutContext getContext()
 */
class CouponCodeRepository extends FieldRepository
{
    public function __construct(
        private string $regex = '([^a-zA-Z0-9\-_]+)'
    ) {
    }

    public function getValue(): mixed
    {
        return $this->filter((string)$this->getContext()->getCheckoutState()->getQuote()->getCouponCode());
    }

    public function saveValue(mixed $value): void
    {
        if (empty($value['task']) || !isset($value['code'])) {
            return;
        }

        $messageManager = $this->getContext()->getMessageManager();
        $quote = $this->getContext()->getCheckoutState()->getQuote();
        if ($value['task'] === 'apply') {
            $couponCode = $this->filter($value['code']);
            $quote->setCouponCode($couponCode);
            $quote->getShippingAddress()->setCollectShippingRates(true);
            $quote->collectTotals();

            $actualCouponCode = $this->getValue();
            if ($couponCode !== $actualCouponCode) {
                $messageManager->addWarningMessage((string)__('Failed to apply coupon code'));
                return;
            }

            $messageManager->addSuccessMessage((string)__('Applied coupon code'));
            return;
        }

        if ($value['task'] === 'remove') {
            $quote->setCouponCode('');
            $this->getGlobalMessageRegistry()->addSuccess((string)__('Removed coupon code'));
        }
    }

    private function filter(string $couponCode): string
    {
        return preg_replace(
            '/'.$this->regex.'/',
            '',
            (string)$couponCode
        );
    }
}

