<?php

declare(strict_types=1);

namespace Yireo\LokiCheckout\Magewire\Resolver;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magewirephp\Magewire\Component;
use Magewirephp\Magewire\Model\Component\ResolverInterface;
use Magewirephp\Magewire\Model\RequestInterface as MagewireRequestInterface;
use Magewirephp\Magewire\Model\Component\Resolver\Layout as LayoutResolver;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LokiResolver implements ResolverInterface
{
    public function __construct(
        private LayoutResolver $layoutResolver,
        private LayoutInterface $layout,
        private ObjectManagerInterface $objectManager,
        private array $componentMapping = []
    ) {
    }

    public function getName(): string
    {
        return 'loki';
    }

    public function complies(AbstractBlock $block): bool
    {
        return true;
    }

    public function construct(AbstractBlock $block): Component
    {
        $component = $this->layoutResolver->construct($block);
        return $component->setResolver($this);
    }

    public function reconstruct(MagewireRequestInterface $request): Component
    {
        $blockName = $request->getFingerprint('name');
        if (!isset($this->componentMapping[$blockName])) {
            throw new HttpException(
                404,
                sprintf('Magewire component "%1s" could not be found', $request->getFingerprint('name'))
            );
        }

        /** @var Component $component */
        $componentClass = $this->componentMapping[$blockName]['componentClass'];
        $component = $this->objectManager->get($componentClass);
        $component->name = $blockName;
        $component->id = $blockName;

        $this->layout->getUpdate()->load(['checkout_index_index']);
        $this->layout->getUpdate()->removeHandle('default'); // @todo: Is this working faster?
        $this->layout->generateXml();
        $this->layout->generateElements();
        $block = $this->layout->getBlock($blockName);

        $component->setParent($block);
        $component->setResolver($this);
        $component->setRequest($request);

        return $component;
    }
}
