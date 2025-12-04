<?php declare(strict_types=1);

namespace LokiCheckout\Core\Util\Checkout;

use LokiCheckout\Core\Config\Config;
use Magento\Framework\App\RequestInterface;

class AllowCheckoutAccess
{
    public function __construct(
        private readonly Config $config,
        private readonly RequestInterface $request,
    ) {
    }

    public function allow(): bool
    {
        if (true === $this->config->isEnabled()) {
            return true;
        }

        $requestedLicense = (string)$this->request->getParam('license');
        $configuredLicense = (string)$this->config->getLicenseKey();
        if (strlen($requestedLicense) > 10 && $requestedLicense === $configuredLicense) {
            return true;
        }

        return false;
    }
}
