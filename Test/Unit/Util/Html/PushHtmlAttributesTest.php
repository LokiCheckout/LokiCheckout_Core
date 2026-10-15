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
            ['<div>TEST', '<div data-foo="&quot;&gt;&lt;script&gt;">TEST', ['data-foo' => '"><script>']],
            ['<div>TEST', '<div data-foo="$1 \\1">TEST', ['data-foo' => '$1 \\1']],
            ['<div id="old">TEST', '<div id="a&quot;b">TEST', ['id' => 'a"b']],
            ['<div>TEST', '<div>TEST', ['onclick="alert(1)" x' => 'foo']],
            ['<div>TEST', '<div @input="1">TEST', ['@input' => true]],
        ];
    }
}
