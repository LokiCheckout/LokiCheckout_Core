<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Test\Integration\Fixture;

use Magento\Framework\DataObject;
use Magento\TestFramework\Fixture\RevertibleDataFixtureInterface;

class CartFixture implements RevertibleDataFixtureInterface
{
    public function apply(array $data = []): ?DataObject
    {
        // TODO: Implement apply() method.
    }

    public function revert(DataObject $data): void
    {
        // TODO: Implement revert() method.
    }
}
