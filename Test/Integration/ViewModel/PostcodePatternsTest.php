<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel;

use LokiCheckout\Core\ViewModel\PostcodePatterns;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class PostcodePatternsTest extends TestCase
{
    public function testConfigurationReturnsAllowedPostcodePatterns(): void
    {
        $subject = ObjectManager::getInstance()->create(PostcodePatterns::class);
        $configuration = $subject->getConfiguration();

        $this->assertIsArray($configuration);
        $this->assertNotEmpty($configuration);
        $this->assertArrayHasKey('NL', $configuration);
    }
}
