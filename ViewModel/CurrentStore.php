<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class CurrentStore implements ArgumentInterface
{
    public function __construct(
        private StoreManagerInterface $storeManager,
    ) {
    }

    public function getLocale(): string
    {
        /** @var Store $store */
        $store =$this->storeManager->getStore();
        return $store->getLocaleCode();
    }

    public function getCurrency(): string
    {
        return $this->storeManager->getStore()->getCurrentCurrency()->getCode();
    }
}
