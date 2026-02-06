<?php declare(strict_types=1);

namespace LokiCheckout\Core\Controller\Index;

use LokiCheckout\Core\Step\FinalStep;
use LokiCheckout\Core\Step\FinalStep\HandlerListing;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect as RedirectResult;
use Magento\Framework\Controller\Result\RedirectFactory as RedirectResultFactory;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use RuntimeException;

class Finalize implements HttpGetActionInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly RedirectResultFactory $resultRedirectFactory,
        private readonly FinalStep $finalStep,
        private readonly FormKeyValidator $formKeyValidator,
        private readonly HandlerListing $handlerListing
    ) {
    }

    public function execute(): RedirectResult
    {
        if (false === $this->formKeyValidator->validate($this->request)) {
            throw new RuntimeException('Invalid form key.');
        }

        $params = (array)$this->request->getParams();
        $redirectUrl = base64_decode((string)$this->request->getParam('loki_redirect_url'));

        foreach ($this->handlerListing->getHandlers() as $handler) {
            $params = $handler->handle($params);
        }

        $this->finalStep->submit(false);

        $redirectUrl = $this->appendParams($redirectUrl, $params);
        $result = $this->resultRedirectFactory->create();
        return $result->setUrl($redirectUrl);
    }

    private function appendParams(string $url, array $params): string
    {
        if (isset($params['loki_redirect_url'])) {
            unset($params['loki_redirect_url']);
        }

        $urlParams = http_build_query($params);
        if (str_contains($url, '?')) {
            return $url.'&' . $urlParams;
        }

        return $url.'?' . $urlParams;
    }
}
