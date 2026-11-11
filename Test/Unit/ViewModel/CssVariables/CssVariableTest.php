<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\ViewModel\CssVariables;

use LokiCheckout\Core\ViewModel\CssVariables\CssVariable;
use PHPUnit\Framework\TestCase;

class CssVariableTest extends TestCase
{
    public function testNameWithoutPrefixGetsPrefixed(): void
    {
        $this->assertSame('--primary-color', (new CssVariable('primary-color', '#f00'))->getName());
    }

    public function testNameWithPrefixIsNotDoubled(): void
    {
        $this->assertSame('--primary-color', (new CssVariable('--primary-color', '#f00'))->getName());
    }

    public function testWhitespaceIsTrimmed(): void
    {
        $this->assertSame('--primary-color', (new CssVariable(' primary-color ', '#f00'))->getName());
    }
}
