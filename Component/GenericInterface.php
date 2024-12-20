<?php

namespace Yireo\LokiCheckout\Component;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiComponents\Component\ComponentInterface;

interface GenericInterface extends ComponentInterface
{
    public function isLazyLoad(): bool;
    public function isAllowRendering(): bool;
    public function getDomId(): string;
    public function setBlock(AbstractBlock $block);
    public function getBlock(): AbstractBlock;
    public function hasBlock(): bool;
    public function getTargets(): array;
}
