<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util;

use LokiCheckout\Core\Util\CurrentTheme;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class CurrentThemeTest extends TestCase
{
    private CurrentTheme $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(CurrentTheme::class);
    }

    public function testThemeDetectionIsBoolean(): void
    {
        $this->assertIsBool($this->subject->isHyva());
        $this->assertIsBool($this->subject->isLuma());
        $this->assertIsBool($this->subject->isBreeze());
    }

    public function testAtMostOneThemeFamilyMatches(): void
    {
        $matches = 0;
        $matches += $this->subject->isHyva() ? 1 : 0;
        $matches += $this->subject->isLuma() ? 1 : 0;
        $matches += $this->subject->isBreeze() ? 1 : 0;

        $this->assertLessThanOrEqual(1, $matches);
    }
}
