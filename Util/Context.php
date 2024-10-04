<?php declare(strict_types=1);

namespace Yireo\MagewireCheckout\Util;

use Magento\Eav\Model\Attribute;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlFactory;
use Yireo\MagewireCheckout\ViewModel\CheckoutState;

class Context
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly EavConfig $eavConfig,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly FieldTypeLocator $fieldTypeLocator,
        private readonly UrlFactory $urlFactory,
    ) {
    }

    public function getCheckoutState(): CheckoutState
    {
        return $this->checkoutState;
    }

    public function getEavConfig(): EavConfig
    {
        return $this->eavConfig;
    }

    public function getScopeConfig(): ScopeConfigInterface
    {
        return $this->scopeConfig;
    }

    public function getFieldTypeLocator(): FieldTypeLocator
    {
        return $this->fieldTypeLocator;
    }

    public function getCheckoutUrl(): string
    {
        return $this->urlFactory->create()->getUrl('checkout/index/index');
    }

    public function getAttribute(string $attributeCode): ?AbstractAttribute
    {
        return $this->eavConfig->getAttribute('customer_address', $attributeCode);
    }
}
