<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Shipment\DeliveryDate;

use Magento\Framework\ObjectManagerInterface;

class TimeframeFactory
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager
    ) {
    }

    public function createFromDates(
        string $id,
        string $code,
        string $label,
        string $fromDate,
        string $toDate,
        float $price
    ): Timeframe {
        return $this->objectManager->create(Timeframe::class, [
            'id' => $id,
            'code' => $code,
            'label' => $label,
            'fromTimestamp' => strtotime($fromDate),
            'toTimestamp' => strtotime($toDate),
            'price' => $price,
        ]);
    }
}
