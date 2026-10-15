<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Plugin;

use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Util\AddressPreviewFlag;
use Magento\Framework\Filter\Template;

class ModifyAddressTemplateVariablesPlugin
{
    public function __construct(
        private readonly Config $config,
        private readonly AddressPreviewFlag $addressPreviewFlag,
    ) {
    }

    public function beforeSetVariables(Template $template, array $variables = [])
    {
        if (false === $this->addressPreviewFlag->isActive()) {
            return [$variables];
        }

        if ($this->config->capitalizeCountryInPreview() && isset($variables['country'])) {
            $variables['country'] = strtoupper((string)$variables['country']);
        }

        return [$variables];
    }
}
