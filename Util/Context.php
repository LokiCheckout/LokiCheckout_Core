<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util;

use Magento\Eav\Model\Attribute;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlFactory;
use Yireo\LokiCheckout\ViewModel\CheckoutState;

/**
 * @deprecated: Inject individual classes instead
 */
class Context
{
    public function __construct(
        private readonly CheckoutState $checkoutState,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly FieldTemplateLocator $fieldTemplateLocator,
        private readonly UrlFactory $urlFactory,
    ) {
    }

    public function getCheckoutState(): CheckoutState
    {
        return $this->checkoutState;
    }

    public function getScopeConfig(): ScopeConfigInterface
    {
        return $this->scopeConfig;
    }

    public function getFieldTemplateLocator(): FieldTemplateLocator
    {
        return $this->fieldTemplateLocator;
    }

    public function getCheckoutUrl(): string
    {
        return $this->urlFactory->create()->getUrl('checkout/index/index');
    }
}
