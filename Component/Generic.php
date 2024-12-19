<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiComponents\Component\ComponentInterface;

abstract class Generic implements GenericInterface, ComponentInterface
{
    protected bool $visible = true;
    protected bool $lazyLoad = true;
    protected bool $allowRendering = true;
    protected string $domId = '';
    protected ?AbstractBlock $block = null;

    public function initialize(): void
    {
    }

    /**
     * @return bool
     */
    public function isLazyLoad(): bool
    {
        return $this->lazyLoad;
    }

    public function isAllowRendering(): bool
    {
        return $this->allowRendering;
    }

    public function getDomId(): string
    {
        $domId = trim((string)$this->block->getDomId());
        if (!empty($domId)) {
            return $domId;
        }

        return $this->block->getNameInLayout();
    }

    public function setBlock(AbstractBlock $block)
    {
        $this->block = $block;
    }

    public function getBlock(): AbstractBlock
    {
        return $this->block;
    }

    public function hasBlock(): bool
    {
        return $this->block instanceof AbstractBlock;
    }

    public function getTargets(): array
    {
        return [];
    }
}
