<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Mutator\Form\Field;

use Magento\Framework\View\Element\AbstractBlock;
use Yireo\LokiComponents\Component\ComponentInterface;

abstract class Generic
{
    protected ?AbstractBlock $block = null;
    protected ?ComponentInterface $component = null;

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

    public function getComponent(): ?ComponentInterface
    {
        return $this->component;
    }

    public function setComponent(ComponentInterface $component): void
    {
        $this->component = $component;
    }

    public function hasComponent(): bool
    {
        return $this->component instanceof ComponentInterface;
    }
}
