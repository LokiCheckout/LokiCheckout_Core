<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Observer;

use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RegisterModuleForHyvaConfig implements ObserverInterface
{
    private ComponentRegistrar $componentRegistrar;

    public function __construct(ComponentRegistrar $componentRegistrar)
    {
        $this->componentRegistrar = $componentRegistrar;
    }

    public function execute(Observer $observer)
    {
        $config = $observer->getData('config');
        $extensions = $config->hasData('extensions') ? $config->getData('extensions') : [];

        $path = $this->componentRegistrar->getPath(ComponentRegistrar::MODULE, 'Yireo_LokiCheckout');

        $extensions[] = ['src' => trim(substr($path, strlen(BP) + 1), '/')];

        $config->setData('extensions', $extensions);
    }
}
