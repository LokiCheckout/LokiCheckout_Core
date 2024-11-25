<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use InvalidArgumentException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\BlockInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magewirephp\Magewire\Component;
use RuntimeException;

class BlockRenderer
{
    private array $counters = [];

    public function __construct(
        private LayoutInterface $layout,
        private string          $scriptContainer = 'loki-checkout.script.alpine-components'
    ) {
    }

    public function getChild(string $blockAlias, AbstractBlock $parentBlock, array $data = []): AbstractBlock
    {
        $block = $parentBlock->getChildBlock($blockAlias);
        if (false === $block instanceof AbstractBlock) {
            throw new RuntimeException((string)__('No block alias "%1"', $blockAlias));
        }

        $this->populateBlock($block, $parentBlock, $data);
        $this->setIncrementalName($block);

        return $block;
    }

    public function getChildHtml(string $blockAlias, AbstractBlock $parentBlock, array $data = [])
    {
        try {
            return (string)$this->getChild($blockAlias, $parentBlock, $data)?->toHtml();
        } catch (RuntimeException|InvalidArgumentException $e) {
            return '<!-- ' . $e->getMessage() . ' -->'; // @todo: Only report this in debug mode
        }
    }

    /**
     * @param string             $blockIdentifier
     * @param AbstractBlock|null $ancestorBlock
     * @param array              $data
     *
     * @param string             $blockName
     *
     * @return Template
     * @deprecated Use getBlockByName or getBlockByTemplate instead
     */
    public function get(
        string         $blockIdentifier,
        ?AbstractBlock $ancestorBlock = null,
        array          $data = [],
        string         $blockName = '',
    ): AbstractBlock {
        if (str_ends_with($blockIdentifier, '.phtml')) {
            return $this->getBlockWithTemplate($blockIdentifier, $ancestorBlock, $data);
        }

        return $this->getBlockByName($blockName, $ancestorBlock, $data);
    }

    /**
     * @param string             $blockName
     * @param AbstractBlock|null $ancestorBlock
     * @param array              $data
     *
     * @return AbstractBlock
     */
    public function getBlockByName(string $blockName, ?AbstractBlock $ancestorBlock = null, array $data = []): AbstractBlock
    {
        $block = $this->layout->getBlock($blockName);
        if (false === $block instanceof AbstractBlock) {
            throw new RuntimeException((string)__('No block found with template "%1"', $blockName));
        }

        $this->populateBlock($block, $ancestorBlock, $data);

        return $block;
    }

    /**
     * @param string             $templateName
     * @param AbstractBlock|null $ancestorBlock
     * @param array              $data
     *
     * @return Template
     */
    public function getBlockWithTemplate(string $templateName, ?AbstractBlock $ancestorBlock = null, array $data = []): Template
    {
        $block = $this->getBlockFromTemplate($templateName);
        if (false === $block instanceof AbstractBlock) {
            throw new RuntimeException((string)__('No block found with template "%1"', $templateName));
        }

        $this->populateBlock($block, $ancestorBlock, $data);

        return $block;
    }

    /**
     * @param string             $templateName
     * @param AbstractBlock|null $ancestorBlock
     * @param array              $data
     *
     * @return string
     */
    public function getBlockTemplate(string $templateName, ?AbstractBlock $ancestorBlock = null, array $data = []): string
    {
        return $this->getBlockWithTemplate($templateName, $ancestorBlock, $data)->toHtml();
    }

    /**
     * @param string             $blockIdentifier
     * @param AbstractBlock|null $parentBlock
     * @param array              $data
     *
     * @return string
     * @deprecated Use getBlockTemplate() or getBlockHtml()
     */
    public function html(string $blockIdentifier, ?AbstractBlock $parentBlock = null, array $data = []): string
    {
        try {
            return (string)$this->get($blockIdentifier, $parentBlock, $data)?->toHtml();
        } catch (RuntimeException|InvalidArgumentException $e) {
            return '<!-- ' . $e->getMessage() . ' -->'; // @todo: Only report this in debug mode
        }
    }

    /**
     * @param string        $templateName
     * @param AbstractBlock $parentBlock
     * @param array         $data
     *
     * @return void
     * @deprecated Use XML layout instead
     */
    public function addScriptTemplate(string $templateName, AbstractBlock $parentBlock, array $data = []): void
    {
        $blockName = $parentBlock->getNameInLayout() . '.script';

        try {
            $block = $this->getBlockByName($blockName);
        } catch (\RuntimeException $e) {
            $block = $this->getBlockFromTemplate($templateName, $blockName);
        }

        $this->populateBlock($block, $parentBlock, $data);
        $this->layout->setChild($this->scriptContainer, $blockName, $blockName);
    }

    /**
     * @param string        $blockName
     * @param AbstractBlock $parentBlock
     * @param array         $data
     *
     * @return void
     */
    public function addScriptBlock(string $blockName, AbstractBlock $parentBlock, array $data = []): void
    {
        $block = $this->layout->getBlock($blockName);
        if (false === $block instanceof AbstractBlock) {
            return;
        }

        $this->populateBlock($block, $parentBlock, $data);
        $this->layout->setChild($this->scriptContainer, $blockName, $blockName);
    }

    /**
     * @param string $templateName
     * @param string $blockName
     *
     * @return Template
     */
    private function getBlockFromTemplate(string $templateName, string $blockName = ''): Template
    {
        if (false === strstr($templateName, '::')) {
            $templateName = 'Yireo_LokiCheckout::' . $templateName;
        }

        return $this->layout->createBlock(Template::class, $blockName)->setTemplate($templateName);
    }

    /**
     * @param AbstractBlock      $block
     * @param AbstractBlock|null $ancestorBlock
     *
     * @return void
     */
    private function populateBlock(AbstractBlock $block, ?AbstractBlock $ancestorBlock = null, array $data = []): void
    {
        if (false === $ancestorBlock instanceof AbstractBlock) {
            return;
        }

        $block->setAncestorBlock($ancestorBlock);
        $block->addData($data);

        if ($block->getData('magewire') instanceof Component) {
            return;
        }

        $component = $ancestorBlock->getData('magewire');
        if ($component instanceof Component) {
            $block->setData('component', $component);
        }

        $component = $ancestorBlock->getData('component');
        if ($component instanceof Component) {
            $block->setData('component', $component);
        }
    }

    private function setIncrementalName(AbstractBlock $block)
    {
        $nameInLayout = $block->getNameInLayout();
        if (preg_match('/\.(\d)$/', $nameInLayout, $match)) {
            $nameInLayout = preg_replace('/\.\d$/', '', $nameInLayout);
            $increment = $match[1];
        } else {
            $increment = 0;
        }

        if (isset($this->counters[$nameInLayout])) {
            $increment++;
            $block->setNameInLayout($nameInLayout . '.' . $increment);
            $this->counters[$nameInLayout] = 0;
        } else {
            $this->counters[$nameInLayout] = 0;
        }
    }
}
