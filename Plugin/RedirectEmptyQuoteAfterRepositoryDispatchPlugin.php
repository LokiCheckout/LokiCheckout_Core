<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Plugin;

use Exception;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlFactory;
use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiComponents\Exception\RedirectException;
use Yireo\LokiComponents\Util\Controller\RepositoryDispatcher;

class RedirectEmptyQuoteAfterRepositoryDispatchPlugin
{
    public function __construct(
        private readonly UrlFactory $urlFactory,
        private readonly ManagerInterface $messageManager,
        private readonly array $allowedExceptionClasses = []
    ) {
    }

    public function aroundDispatch(
        RepositoryDispatcher $repositoryDispatcher,
        callable $proceed,
        AbstractBlock $block,
        mixed $componentData
    ): void {
        try {
            $proceed($block, $componentData);
        } catch (Exception $e) {
            $this->handleException($e);
        }
    }

    private function handleException(Exception $e): void
    {
        if (false === $this->isExceptionAllowed($e)) {
            throw $e;
        }

        $this->messageManager->addErrorMessage(get_class($e) . ': ' . $e->getMessage());
        $url = $this->urlFactory->create()->getUrl('checkout/cart');
        throw new RedirectException($url);
    }

    private function isExceptionAllowed(Exception $e): bool
    {
        foreach ($this->allowedExceptionClasses as $allowedExceptionClass) {
            if ($e instanceof $allowedExceptionClass) {
                return true;
            }
        }

        return false;
    }
}
