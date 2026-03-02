<?php declare(strict_types=1);

namespace LokiCheckout\Core\Controller\Index;

use Loki\Components\Exception\RedirectException;
use LokiCheckout\Core\Step\FinalStep;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect as RedirectResult;
use Magento\Framework\Controller\Result\RedirectFactory as RedirectResultFactory;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\Exception\CouldNotSaveException;
use RuntimeException;

class Finalize implements HttpGetActionInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly RedirectResultFactory $resultRedirectFactory,
        private readonly FinalStep $finalStep,
        private readonly FormKeyValidator $formKeyValidator,
    ) {
    }

    /**
     * @return RedirectResult
     * @throws CouldNotSaveException
     */
    public function execute(): RedirectResult
    {
        if (false === $this->formKeyValidator->validate($this->request)) {
            throw new RuntimeException('Invalid form key.');
        }

        $redirectUrl = '';
        
        try {
            $this->finalStep->submit();
        } catch (RedirectException $e) {
            $redirectUrl = $e->getMessage();
        }

        $result = $this->resultRedirectFactory->create();
        return $result->setUrl($redirectUrl);
    }
}
