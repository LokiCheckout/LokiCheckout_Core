<?php declare(strict_types=1);

namespace LokiCheckout\Core\Controller\Index;

use Loki\Components\Exception\RedirectException;
use LokiCheckout\Core\Step\FinalStep;
use LokiCheckout\Core\Step\FinalStep\FinalizeToken;
use LokiCheckout\Core\Util\Checkout\AllowCheckoutAccess;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Forward as ForwardResult;
use Magento\Framework\Controller\Result\ForwardFactory as ForwardResultFactory;
use Magento\Framework\Controller\Result\Redirect as RedirectResult;
use Magento\Framework\Controller\Result\RedirectFactory as RedirectResultFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Message\ManagerInterface as MessageManager;

class Finalize implements HttpGetActionInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly RedirectResultFactory $resultRedirectFactory,
        private readonly ForwardResultFactory $resultForwardFactory,
        private readonly FinalStep $finalStep,
        private readonly FinalizeToken $finalizeToken,
        private readonly AllowCheckoutAccess $allowCheckoutAccess,
        private readonly MessageManager $messageManager,
    ) {
    }

    /**
     * @return RedirectResult|ForwardResult
     * @throws CouldNotSaveException
     */
    public function execute(): RedirectResult|ForwardResult
    {
        if (false === $this->allowCheckoutAccess->allow()) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $token = (string)$this->request->getParam(FinalizeToken::PARAM_NAME);
        if (false === $this->finalizeToken->validate($token)) {
            $this->messageManager->addErrorMessage(__('Your session has expired. Please try again.'));

            return $this->resultRedirectFactory->create()->setPath('checkout');
        }

        $redirectUrl = '';

        try {
            $this->finalStep->submit();
        } catch (RedirectException $e) {
            $redirectUrl = $e->getUrl();
        }

        $result = $this->resultRedirectFactory->create();
        return $result->setUrl($redirectUrl);
    }
}
