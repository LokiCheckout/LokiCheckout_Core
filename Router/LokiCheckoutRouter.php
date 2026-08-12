<?php declare(strict_types=1);

namespace LokiCheckout\Core\Router;

use LokiCheckout\Core\Controller\Index\Index;
use LokiCheckout\Core\Util\Checkout\AllowCheckoutAccess;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;

class LokiCheckoutRouter implements RouterInterface
{
    public function __construct(
        private readonly ActionFactory $actionFactory,
        private readonly AllowCheckoutAccess $allowCheckoutAccess,
    ) {
    }

    public function match(RequestInterface $request): ActionInterface|null
    {
        /** @var HttpRequest $request */
        $identifier = trim($request->getPathInfo(), '/');
        $parts = explode('/', $identifier);

        if ($parts[0] !== 'checkout') {
            return null;
        }

        $matches = match (count($parts)) {
            1 => true,
            2 => $parts[1] === 'index',
            3 => $parts[1] === 'index' && $parts[2] === 'index',
            default => false,
        };

        if (false === $matches) {
            return null;
        }

        if (false === $this->allowCheckoutAccess->allow()) {
            return null;
        }

        $request->setRouteName('checkout');
        $request->setControllerName('index');
        $request->setActionName('index');
        $request->setDispatched();

        return $this->actionFactory->create(Index::class);
    }
}
