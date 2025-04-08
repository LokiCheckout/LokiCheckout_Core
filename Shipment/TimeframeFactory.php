<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Shipment;

use Magento\Framework\ObjectManagerInterface;

class TimeframeFactory
{
    public function __construct(
        private ObjectManagerInterface $objectManager
    ) {
    }

    public function createFromDates(string $fromDate, string $toDate, string $code, string $label, float $price): Timeframe
    {
        return $this->objectManager->create(Timeframe::class, [
            'fromTimestamp' => strtotime($fromDate),
            'toTimestamp' => strtotime($toDate),
            'code' => $code,
            'label' => $label,
            'price' => $price,
        ]);
    }
}
