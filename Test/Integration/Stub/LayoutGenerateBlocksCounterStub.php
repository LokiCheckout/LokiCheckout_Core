<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Stub;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class LayoutGenerateBlocksCounterStub implements ObserverInterface
{
    private int $count = 0;

    public function execute(Observer $observer)
    {
        $this->count++;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
