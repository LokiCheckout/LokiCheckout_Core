<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Block;

use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use RuntimeException;

class TemplateRenderer extends AbstractRenderer
{
    public function __construct(
        private LayoutInterface $layout,
    ) {
    }

    /**
     * @param string $templateName
     * @param AbstractBlock $ancestorBlock
     * @param array $data
     *
     * @return Template
     */
    public function get(
        string $templateName,
        array $data = []
    ): Template {
        $blockAlias = $this->createChildAlias($this->ancestorBlock, $data);
        $blockName = $this->ancestorBlock->getNameInLayout().'.'.$blockAlias;
        $block = $this->createBlockFromTemplate($templateName, $blockName);

        if (false === $block instanceof AbstractBlock) {
            throw new RuntimeException((string)__('No block found with template "%1"', $templateName));
        }

        $this->populateBlock($block, $data);

        return $block;
    }

    /**
     * @param string $templateName
     * @param array $data
     *
     * @return string
     */
    public function html(
        string $templateName,
        array $data = []
    ): string {
        return $this->get($templateName, $data)->toHtml();
    }

    /**
     * @param string $templateName
     * @param string $blockName
     *
     * @return Template
     */
    private function createBlockFromTemplate(string $templateName, string $blockName): Template
    {
        if (false === str_contains($templateName, '::')) {
            $templateName = 'Yireo_LokiCheckout::'.$templateName;
        }

        if (false === str_contains($templateName, '.phtml')) {
            $templateName = $templateName.'.phtml';
        }

        $block = $this->layout->getBlock($blockName);
        if ($block instanceof Template) {
            return $block;
        }

        return $this->layout->createBlock(Template::class, $blockName)->setTemplate($templateName);
    }

    private function createChildAlias(AbstractBlock $ancestorBlock, array $data): string
    {
        if (isset($data['alias'])) {
            return $data['alias'];
        }

        return 'child'.$this->getCounter($ancestorBlock);
    }
}
