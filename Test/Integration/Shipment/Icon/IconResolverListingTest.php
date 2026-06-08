<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Shipment\Icon;

use LokiCheckout\Core\Shipment\Icon\IconResolverInterface;
use LokiCheckout\Core\Shipment\Icon\IconResolverListing;
use LokiCheckout\Core\Shipment\Icon\LayoutIconResolver;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class IconResolverListingTest extends TestCase
{
    public function testLayoutResolverIsAlwaysFirst(): void
    {
        $listing = ObjectManager::getInstance()->get(IconResolverListing::class);
        $resolvers = $listing->getIconResolvers();

        $this->assertNotEmpty($resolvers);
        $this->assertInstanceOf(LayoutIconResolver::class, $resolvers[0]);

        foreach ($resolvers as $resolver) {
            $this->assertInstanceOf(IconResolverInterface::class, $resolver);
        }
    }
}
