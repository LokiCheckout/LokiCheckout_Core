<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Locale\Resolver as LocaleResolver;

class CurrentStore implements ArgumentInterface
{
    public function __construct(
        private StoreManagerInterface $storeManager,
        private LocaleResolver $localeResolver,
    ) {
    }

    public function getLocale(): string
    {
        return (string)$this->localeResolver->getLocale();
    }

    public function getCurrency(): string
    {
        /** @var Store $store */
        $store = $this->storeManager->getStore();

        return $store->getCurrentCurrency()->getCode();
    }
}
