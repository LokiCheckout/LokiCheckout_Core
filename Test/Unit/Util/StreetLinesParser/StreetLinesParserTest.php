<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Util\StreetLinesParser;

use LokiCheckout\Core\Util\StreetLinesParser\StreetLinesParser;
use PHPUnit\Framework\TestCase;

class StreetLinesParserTest extends TestCase
{
    /**
     * @return void
     * @dataProvider getTestArguments
     */
    public function testParseStreetParts(string $countryId, array $streetParts, array $expectedNewStreetParts)
    {
        $streetLinesParser = new StreetLinesParser();
        $newStreetParts = $streetLinesParser->parseStreetParts($streetParts, $countryId);
        $this->assertEquals($expectedNewStreetParts, $newStreetParts);
    }

    public static function getTestArguments(): array
    {
        return [
            ['NL', ['Amalialaan 126 D A'], ['Amalialaan', '126', 'D', 'A']],
            ['NL', ['Amalialaan 126 D'], ['Amalialaan', '126', 'D']],
            ['NL', ['Amalialaan 126D'], ['Amalialaan', '126', 'D']],
            ['NL', ['Frans Halslaan 42'], ['Frans Halslaan', '42']],
            ['NL', ['Frans Halslaan 42 HS'], ['Frans Halslaan', '42', 'HS']],
            ['NL', ['Frans Halslaan 42HS'], ['Frans Halslaan', '42', 'HS']],
            ['NL', ['Frans Halslaan'], ['Frans Halslaan']],
            ['NL', ['3 van der Zwaanlaan 42'], ['3 van der Zwaanlaan', '42']],
            ['NL', ['3 van der Zwaanlaan 42 HS'], ['3 van der Zwaanlaan', '42', 'HS']],
            ['NL', ['3 van der Zwaanlaan 42HS'], ['3 van der Zwaanlaan', '42', 'HS']],
            ['NL', ['3 van der Zwaanlaan'], ['3 van der Zwaanlaan']],
        ];
    }
}
