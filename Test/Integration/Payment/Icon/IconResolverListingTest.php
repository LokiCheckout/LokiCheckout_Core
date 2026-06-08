<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Payment\Icon;

use InvalidArgumentException;
use LokiCheckout\Core\Payment\Icon\IconResolverInterface;
use LokiCheckout\Core\Payment\Icon\IconResolverListing;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class IconResolverListingTest extends TestCase
{
    public function testDiConfiguredResolversImplementInterface(): void
    {
        $listing = ObjectManager::getInstance()->get(IconResolverListing::class);

        foreach ($listing->getIconResolvers() as $resolver) {
            $this->assertInstanceOf(IconResolverInterface::class, $resolver);
        }
    }

    public function testInvalidResolverThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new IconResolverListing([new \stdClass()]);
    }
}
