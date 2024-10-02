<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Quote\Model\Quote;
use Yireo\MagewireCheckout\Util\StepNavigator;

class CheckoutState implements ArgumentInterface
{

    public function __construct(
        private readonly CheckoutSession $checkoutSession,
        private readonly CustomerSession $customerSession,
        private readonly UrlFactory $urlFactory,
        private readonly StepNavigator $stepNavigator
    ) {
    }

    public function getStepNavigator(): StepNavigator
    {
        return $this->stepNavigator;
    }

    public function getQuote(): Quote
    {
        return $this->checkoutSession->getQuote();
    }

    public function getCustomerSession(): CustomerSession
    {
        return $this->customerSession;
    }

    public function getCheckoutUrl(): string
    {
        return $this->urlFactory->create()->getUrl('checkout/index/index');
    }
}
