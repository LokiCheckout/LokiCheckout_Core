<?php declare(strict_types=1);

namespace LokiCheckout\Core\Controller\Index;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Checkout\Helper\Data;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Message\Manager;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\Result\PageFactory as PageResultFactory;
use Magento\Framework\Controller\Result\RedirectFactory as RedirectResultFactory;
use Magento\Framework\Controller\Result\Redirect as RedirectResult;

class Index implements HttpGetActionInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly PageResultFactory $pageResultFactory,
        private readonly RedirectResultFactory $redirectResultFactory,
        private readonly CustomerSession $customerSession,
        private readonly CheckoutSession $checkoutSession,
        private readonly Data $checkoutHelper,
        private readonly Manager $messageManager,
        private readonly AccountManagementInterface $accountManagement,
        private readonly UrlFactory $urlFactory
    ) {
    }

    public function execute()
    {
        if (false === $this->checkoutState->hasQuote()) {
            return $this->redirectResultFactory->create()->setPath('checkout/cart');
        }

        $quote = $this->checkoutState->getQuote();
        if (!$this->customerSession->isLoggedIn() && !$this->checkoutHelper->isAllowedGuestCheckout($quote)) {
            $this->messageManager->addErrorMessage(__('Guest checkout is disabled.'));
            return $this->redirectResultFactory->create()->setPath('checkout/cart');
        }

        $this->customerSession->regenerateId();
        $this->checkoutSession->setCartWasUpdated(false);
        $this->checkoutSession->setLokiRedirectUrl('');
        $this->customerSession->setBeforeAuthUrl(
            $this->urlFactory->create()->getUrl('checkout/index/index')
        );

        $result = $this->validateCustomer();
        if ($result instanceof RedirectResult) {
            return $result;
        }

        $this->assignCustomerToQuote();

        $pageResult = $this->pageResultFactory->create();
        $pageResult->getConfig()->getTitle()->set(__('Checkout'));
        $pageResult->addHandle('checkout_index_index');
        $pageResult->addHandle('loki_checkout');

        return $pageResult;
    }

    private function validateCustomer(): RedirectResult|bool
    {
        if (!$this->customerSession->isLoggedIn()) {
            return true;
        }

        $customer = $this->customerSession->getCustomerData();
        if (!$customer) {
            return true;
        }

        $validationResult = $this->accountManagement->validate($customer);
        if ($validationResult->isValid()) {
            return true;
        }

        foreach ($validationResult->getMessages() as $error) {
            $this->messageManager->addErrorMessage($error);
        }

        return $this->redirectResultFactory->create()->setPath('checkout/cart');
    }

    private function assignCustomerToQuote(): void
    {
        if (!$this->customerSession->isLoggedIn()) {
            return;
        }

        $customer = $this->customerSession->getCustomerData();
        if (!$customer) {
            return;
        }

        $quote = $this->checkoutState->getQuote();
        $quote->assignCustomerWithAddressChange(
            $customer,
            $quote->getBillingAddress(),
            $quote->getShippingAddress(),
        );
    }
}
