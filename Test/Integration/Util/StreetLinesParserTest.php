<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util;

use LokiCheckout\Core\Util\StreetLinesParser\StreetLinesParser;
use Magento\Framework\App\ObjectManager;
use PHPUnit\Framework\TestCase;

class StreetLinesParserTest extends TestCase
{
    public function testSupportedCountriesAreConfiguredViaDi(): void
    {
        $streetLinesParser = ObjectManager::getInstance()->get(StreetLinesParser::class);

        $this->assertEquals(['Amalialaan', '126', 'D'], $streetLinesParser->parseStreetParts(['Amalialaan 126D'], 'NL'));
        $this->assertEquals(['Rue Royale', '15', 'B'], $streetLinesParser->parseStreetParts(['Rue Royale 15B'], 'BE'));
        $this->assertEquals(['Hauptstraße 12'], $streetLinesParser->parseStreetParts(['Hauptstraße 12'], 'DE'));
    }
}
