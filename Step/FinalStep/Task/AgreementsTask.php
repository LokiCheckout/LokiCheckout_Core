<?php declare(strict_types=1);

namespace LokiCheckout\Core\Step\FinalStep\Task;

use Loki\Components\Exception\RedirectException;
use Magento\Checkout\Api\AgreementsValidatorInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlFactory;
use Magento\Quote\Model\Quote;

class AgreementsTask implements TaskInterface
{
    public function __construct(
        private readonly AgreementsValidatorInterface $agreementsValidator,
        private readonly CheckoutSession $checkoutSession,
        private readonly ManagerInterface $messageManager,
        private readonly UrlFactory $urlFactory,
    ) {
    }

    public function beforePlaceOrder(Quote $quote): void
    {
        $agreementIds = (array)$this->checkoutSession->getData('agreement_ids');
        if ($this->agreementsValidator->isValid($agreementIds)) {
            return;
        }

        $this->messageManager->addErrorMessage(
            __("The order wasn't placed. First, agree to the terms and conditions, then try placing your order again.")
        );

        throw (new RedirectException())->setUrl($this->urlFactory->create()->getUrl('checkout'));
    }

    public function afterPlaceOrder(int $orderId): void
    {
    }
}
