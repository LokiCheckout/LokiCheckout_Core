<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Stub;

use Magento\Framework\ObjectManagerInterface;

class SimpleObjectManager implements ObjectManagerInterface
{
    public function create($type, array $arguments = [])
    {
        $object = new $type(...$arguments);
        return $object;
    }

    public function get($type)
    {
        return $this->create($type);
    }

    public function configure(array $configuration)
    {
    }
}
