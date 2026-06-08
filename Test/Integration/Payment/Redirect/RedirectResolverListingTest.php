<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Payment\Redirect;

use InvalidArgumentException;
use LokiCheckout\Core\Payment\Redirect\RedirectResolverInterface;
use LokiCheckout\Core\Payment\Redirect\RedirectResolverListing;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class RedirectResolverListingTest extends TestCase
{
    public function testDiConfiguredResolversImplementInterface(): void
    {
        $listing = ObjectManager::getInstance()->get(RedirectResolverListing::class);

        foreach ($listing->getRedirectResolvers() as $resolver) {
            $this->assertInstanceOf(RedirectResolverInterface::class, $resolver);
        }
    }

    public function testInvalidResolverThrows(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new RedirectResolverListing([new \stdClass()]);
    }
}
