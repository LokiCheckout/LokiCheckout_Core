<?php declare(strict_types=1);

namespace LokiCheckout\Core\Shipment;

use Magento\Framework\ObjectManagerInterface;

class DeliveryDateFactory
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager
    ) {
    }

    public function create(
        string $id,
        int $timestamp,
        array $timeframes = []
    ): DeliveryDate {
        return $this->objectManager->create(DeliveryDate::class, [
            'id' => $id,
            'timestamp' => $timestamp,
            'timeframes' => $timeframes,
        ]);
    }
}
