<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Util\Html;

use LokiCheckout\Core\Util\Html\PushHtmlAttributes;
use PHPUnit\Framework\TestCase;

class PushHtmlAttributesTest extends TestCase
{
    /**
     * @return void
     * @dataProvider getTestValues
     */
    public function testExecute(mixed $html, mixed $expectedHtml, array $attributes = [])
    {
        $pushHtmlAttributes = new PushHtmlAttributes();
        $this->assertEquals($expectedHtml, $pushHtmlAttributes->execute($html, $attributes));
    }

    public static function getTestValues(): array
    {
        return [
            ['', '', ['id' => 'foobar']],
            ['TEST', 'TEST', ['id' => 'foobar']],
            ['<>TEST', '<>TEST', ['id' => 'foobar']],
            ['<script>TEST', '<script>TEST', ['id' => 'foobar']],
            ['<div><div></div>TEST', '<div id="foobar"><div></div>TEST', ['id' => 'foobar']],
            ['<div id="old"><div></div>TEST', '<div id="new"><div></div>TEST', ['id' => 'new']],
        ];
    }
}
