<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Layout;

use LokiCheckout\Core\ViewModel\FastCheckout;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Text;
use Magento\Framework\View\LayoutInterface;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;

#[DataFixture(GuestCartFixture::class, as: 'cart')]
#[AppArea('frontend')]
class FastCheckoutBlockTest extends AbstractLayoutTestCase
{
    private const BLOCK_NAME = 'loki-checkout.fast_checkout';

    public function testBlockHasFastCheckoutViewModel(): void
    {
        $block = $this->getFastCheckoutBlock($this->getLayout());

        $this->assertInstanceOf(FastCheckout::class, $block->getData('fast_checkout_view_model'));
    }

    public function testNoHeadingWithoutChildren(): void
    {
        $layout = $this->getLayout();
        $block = $this->getFastCheckoutBlock($layout);
        $this->removeChildren($layout, $block);

        $this->assertSame('', trim((string)$block->toHtml(), " \f\n\r\t\v\x00"));
    }

    public function testNoHeadingWithOnlyNotRenderedOrEmptyChildren(): void
    {
        $layout = $this->getLayout();
        $block = $this->getFastCheckoutBlock($layout);
        $this->removeChildren($layout, $block);
        $this->addNotRenderedChild($layout, 'loki-checkout.fast_checkout.test_not_rendered');
        $this->addTextChild($layout, 'loki-checkout.fast_checkout.test_empty', "  <!-- nothing -->\n");

        $html = (string)$block->toHtml();

        $this->assertStringNotContainsString('Pay faster with', $html);
        $this->assertStringContainsString('Not rendered: loki-checkout.fast_checkout.test_not_rendered', $html);
        $this->assertMatchesRegularExpression('/^<div[^>]*\shidden[\s>]/', trim($html, " \f\n\r\t\v\x00"));
    }

    public function testHeadingWithOneVisibleChild(): void
    {
        $layout = $this->getLayout();
        $block = $this->getFastCheckoutBlock($layout);
        $this->removeChildren($layout, $block);
        $this->addNotRenderedChild($layout, 'loki-checkout.fast_checkout.test_not_rendered');
        $this->addTextChild($layout, 'loki-checkout.fast_checkout.test_visible', '<button>Test Pay</button>');

        $html = (string)$block->toHtml();

        $this->assertStringContainsString('Pay faster with', $html);
        $this->assertStringContainsString('<button>Test Pay</button>', $html);
        $this->assertStringContainsString('Not rendered: loki-checkout.fast_checkout.test_not_rendered', $html);
    }

    private function getFastCheckoutBlock(LayoutInterface $layout): AbstractBlock
    {
        $block = $layout->getBlock(self::BLOCK_NAME);
        $this->assertInstanceOf(AbstractBlock::class, $block);

        return $block;
    }

    private function removeChildren(LayoutInterface $layout, AbstractBlock $block): void
    {
        foreach ($block->getChildNames() as $childName) {
            $layout->unsetChild(self::BLOCK_NAME, $layout->getElementAlias($childName));
        }
    }

    private function addNotRenderedChild(LayoutInterface $layout, string $name): void
    {
        $layout->createBlock(Template::class, $name, [
            'data' => [
                'template' => 'Loki_Components::utils/not-rendered.phtml',
                'not_rendered' => true,
            ],
        ]);
        $layout->setChild(self::BLOCK_NAME, $name, $name);
    }

    private function addTextChild(LayoutInterface $layout, string $name, string $text): void
    {
        /** @var Text $child */
        $child = $layout->addBlock(Text::class, $name, self::BLOCK_NAME);
        $child->setText($text);
    }
}
