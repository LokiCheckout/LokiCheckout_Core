<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration;

use Loki\CssUtils\Util\CssClass;
use Loki\CssUtils\Util\CssClassFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\Fixture\AppArea;

#[AppArea('frontend')]
final class CssUtilsTest extends LokiCheckoutTestCase
{
    private const TEMPLATE = 'LokiCheckout_Core::form/button/step-forward-button-icon.phtml';

    public function testIconScopeCssClassesAreRenderedAndOrdered(): void
    {
        $html = $this->renderStepForwardButton(
            'loki-checkout.test.step-forward-button-icon',
            [
                'icon' => [
                    'override' => 'custom-icon-class',
                ],
            ]
        );

        $this->assertStringContainsString('inline-block mr-3', $html);
        $this->assertStringContainsString('animate-spin', $html);
        $this->assertStringContainsString('custom-icon-class', $html);
        $this->assertStringContainsString('scope-icon', $html);
        $this->assertStringContainsString('__icon', $html);
        $this->assertStringNotContainsString('class=""', $html);

        $this->assertMatchesRegularExpression(
            '/__icon\s+scope-icon\s+inline-block mr-3\s+custom-icon-class/',
            $html,
            'Non-spinner icon div does not follow expected CSS class ordering'
        );

        $this->assertMatchesRegularExpression(
            '/__icon\s+scope-icon\s+inline-block mr-3 animate-spin\s+custom-icon-class/',
            $html,
            'Spinner icon div does not follow expected CSS class ordering'
        );
    }

    public function testBlockScopeCssClassesAreRenderedAndOrdered(): void
    {
        $layout = $this->getObjectManager()->get(LayoutInterface::class);
        $block = $layout->createBlock(
            Template::class,
            'loki-checkout.test.step-forward-button-icon.block'
        );
        $block->setTemplate(self::TEMPLATE);
        $block->setData('css_classes', [
            'block' => [
                'override' => 'custom-block-class',
            ],
            'icon' => [
                'override' => 'custom-icon-class',
            ],
        ]);

        $html = $block->toHtml();
        $this->assertStringContainsString('custom-icon-class', $html);

        $css = $this->getObjectManager()->get(CssClassFactory::class)->create();
        $this->assertInstanceOf(CssClass::class, $css);
        $css->setBlock($block);

        $blockScopeCss = $css('p-4');
        $iconScopeCss = $css('inline-block mr-3', 'icon');

        $this->assertStringContainsString('scope-block', $blockScopeCss);
        $this->assertStringContainsString('p-4', $blockScopeCss);
        $this->assertStringContainsString('custom-block-class', $blockScopeCss);
        $this->assertMatchesRegularExpression(
            '/^\S+\s+scope-block\s+p-4\s+custom-block-class$/',
            $blockScopeCss,
            'Block-scope CSS classes do not follow expected ordering'
        );

        $this->assertStringContainsString('scope-icon', $iconScopeCss);
        $this->assertStringContainsString('__icon', $iconScopeCss);
        $this->assertStringContainsString('inline-block mr-3', $iconScopeCss);
        $this->assertStringContainsString('custom-icon-class', $iconScopeCss);
        $this->assertMatchesRegularExpression(
            '/__icon\s+scope-icon\s+inline-block mr-3\s+custom-icon-class$/',
            $iconScopeCss,
            'Icon-scope CSS classes do not follow expected ordering'
        );
    }

    private function renderStepForwardButton(string $blockName, array $cssClasses): string
    {
        $layout = $this->getObjectManager()->get(LayoutInterface::class);
        $block = $layout->createBlock(Template::class, $blockName);
        $block->setTemplate(self::TEMPLATE);
        $block->setData('css_classes', $cssClasses);

        return $block->toHtml();
    }
}
