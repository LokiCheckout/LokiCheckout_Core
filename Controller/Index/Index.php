<?php declare(strict_types=1);

namespace LokiCheckout\Core\Controller\Index;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Checkout\Helper\Data;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ViewInterface;
use Magento\Framework\Message\Manager;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\Result\PageFactory as PageResultFactory;
use Magento\Framework\Controller\Result\RedirectFactory as RedirectResultFactory;
use Magento\Framework\Controller\Result\Redirect as RedirectResult;

class Index implements HttpGetActionInterface
{
    /**
     * The ViewInterface argument is not used directly. App\View creates its own page result when it is instantiated,
     * which replaces the builder of the shared page config. If this happens while the checkout layout is being
     * generated (because some block depends on it), the layout is built twice, removing child blocks of redeclared
     * elements (like the Hyvä scripts in "head.additional"). Injecting it here makes sure it is instantiated first.
     */
    public function __construct( // @phpstan-ignore constructor.unusedParameter
        private readonly CheckoutState $checkoutState,
        private readonly PageResultFactory $pageResultFactory,
        private readonly RedirectResultFactory $redirectResultFactory,
        private readonly Data $checkoutHelper,
        private readonly Manager $messageManager,
        private readonly AccountManagementInterface $accountManagement,
        private readonly UrlFactory $urlFactory,
        ViewInterface $view,
    ) {
    }

    public function execute()
    {
        if (false === $this->checkoutHelper->canOnepageCheckout()) {
            $this->messageManager->addErrorMessage(__('One-page checkout is turned off.'));
            return $this->redirectResultFactory->create()->setPath('checkout/cart');
        }

        if (false === $this->checkoutState->hasQuote()) {
            return $this->redirectResultFactory->create()->setPath('checkout/cart');
        }

        $quote = $this->checkoutState->getQuote();

        if (!$this->getCustomerSession()->isLoggedIn() && !$this->checkoutHelper->isAllowedGuestCheckout($quote)) {
            $this->messageManager->addErrorMessage(__('Guest checkout is disabled.'));
            return $this->redirectResultFactory->create()->setPath('checkout/cart');
        }

        //$this->getCustomerSession()->regenerateId(); // @todo: Only call this when doing component updates
        $this->getCheckoutSession()->setCartWasUpdated(false);
        $this->getCheckoutSession()->setLokiRedirectUrl('');
        $this->getCustomerSession()->setBeforeAuthUrl(
            $this->urlFactory->create()->getUrl('checkout/index/index')
        );

        $result = $this->validateCustomer();
        if ($result instanceof RedirectResult) {
            return $result;
        }

        $this->assignCustomerToQuote();

        $pageResult = $this->pageResultFactory->create();
        $pageResult->addHandle('checkout_index_index');
        $pageResult->addHandle('loki_checkout');
        $pageResult->getConfig()->getTitle()->set(__('Checkout'));

        return $pageResult;
    }

    private function validateCustomer(): RedirectResult|bool
    {
        if (!$this->getCustomerSession()->isLoggedIn()) {
            return true;
        }

        $customer = $this->getCustomerSession()->getCustomerData();
        if (!$customer) {
            return true;
        }

        $validationResult = $this->accountManagement->validate($customer);
        if ($validationResult->isValid()) {
            return true;
        }

        foreach ($validationResult->getMessages() as $error) {
            $this->messageManager->addErrorMessage(__($error));
        }

        return $this->redirectResultFactory->create()->setPath('checkout/cart');
    }

    private function assignCustomerToQuote(): void
    {
        if (!$this->getCustomerSession()->isLoggedIn()) {
            return;
        }

        $customer = $this->getCustomerSession()->getCustomerData();
        if (!$customer) {
            return;
        }

        $quote = $this->checkoutState->getQuote();
        if ((int)$quote->getCustomerId() === (int)$customer->getId()) {
            return;
        }

        $quote->assignCustomerWithAddressChange(
            $customer,
            $quote->getBillingAddress(),
            $quote->getShippingAddress(),
        );
    }

    private function getCustomerSession(): CustomerSession
    {
        return $this->checkoutState->getCustomerSession();
    }

    private function getCheckoutSession(): CheckoutSession
    {
        return $this->checkoutState->getCheckoutSession();
    }
}
