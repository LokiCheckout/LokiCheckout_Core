<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util;

use LokiCheckout\Core\Util\ValueSanitizer;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;

class ValueSanitizerTest extends TestCase
{
    private ValueSanitizer $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(ValueSanitizer::class);
    }

    /**
     * @dataProvider getValues
     */
    public function testSanitize(string $input, string $expected): void
    {
        $this->assertSame($expected, $this->subject->sanitize($input));
    }

    public static function getValues(): array
    {
        return [
            ['  John  ', 'John'],
            ['<script>alert(1)</script>John', 'alert(1)John'],
            ['<b>Bold</b>', 'Bold'],
            ['Tom & Jerry', 'Tom &amp; Jerry'],
            ['plain', 'plain'],
        ];
    }
}
