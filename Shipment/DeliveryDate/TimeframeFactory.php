<?php declare(strict_types=1);

namespace LokiCheckout\Core\Shipment\DeliveryDate;

use Magento\Framework\ObjectManagerInterface;

class TimeframeFactory
{
    public function __construct(
        private readonly ObjectManagerInterface $objectManager
    ) {
    }

    public function createFromDates(
        string $id,
        string $label,
        array $value,
        string $fromDate,
        string $toDate,
        float $price,
        bool $disabled = false,
        string $carrier = '',
        string $carrierImage = '',
    ): Timeframe {
        return $this->objectManager->create(Timeframe::class, [
            'id' => $id,
            'label' => $label,
            'value' => $value,
            'fromTimestamp' => strtotime($fromDate),
            'toTimestamp' => strtotime($toDate),
            'price' => $price,
            'disabled' => $disabled,
            'carrier' => $carrier,
            'carrierImage' => $carrierImage,
        ]);
    }
}
