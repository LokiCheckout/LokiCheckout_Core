<?php declare(strict_types=1);

namespace LokiCheckout\Core\Shipment\Icon;

use Loki\Components\Util\Block\ImageRenderer;
use Loki\Field\ViewModel\ImageOutput;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\LayoutInterface;

class LayoutIconResolver implements IconResolverInterface
{
    public function __construct(
        private LayoutInterface $layout,
        private ImageRenderer $imageRenderer,
    ) {
    }

    public function resolve(IconResolverContext $iconResolverContext): false|string
    {
        $shipmentMethodCode = $iconResolverContext->getShipmentMethodCode();
        $blockName = 'loki-checkout.shipping.methods.'.$shipmentMethodCode;

        $block = $this->layout->getBlock($blockName);
        if ($block instanceof AbstractBlock) {
            $icon = trim((string)$block->getIcon());
            if (!empty($icon)) {
                $attributes = array_merge(
                    $iconResolverContext->getAttributes(),
                    (array)$block->getIconAttributes()
                );

                return $this->imageRenderer->getByAssetId($icon, $attributes);
            }
        }

        return false;
    }
}
