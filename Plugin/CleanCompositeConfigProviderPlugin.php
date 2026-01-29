<?php
declare(strict_types=1);

namespace LokiCheckout\Core\Plugin;

use Magento\Checkout\Model\CompositeConfigProvider;

class CleanCompositeConfigProviderPlugin
{
    public function afterGetConfig(CompositeConfigProvider $compositeConfigProvider, array $config): array
    {
        // Remove postcodes from the configuration to decrease configuration size
        if (isset($config['postCodes'])) {
            unset($config['postCodes']);
        }

        return $config;
    }
}
