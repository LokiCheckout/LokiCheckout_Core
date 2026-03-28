<?php declare(strict_types=1);

namespace LokiCheckout\Core\Shipment\DeliveryDate;

use IntlDateFormatter;
use JsonSerializable;
use Magento\Framework\Locale\ResolverInterface as LocaleResolver;
use Magento\Framework\Stdlib\DateTime\Timezone;

// @todo Move date formatting to separate class DateFormatter
class Timeframe implements JsonSerializable
{
    public function __construct(
        private readonly LocaleResolver $localeResolver,
        private readonly Timezone $timezone,
        private readonly string $id,
        private readonly array $value,
        private readonly string $label,
        private readonly int $fromTimestamp,
        private readonly int $toTimestamp,
        private readonly float $price = 0,
        private readonly bool $disabled = false,
        private readonly string $carrier = '',
        private readonly string $carrierImage = '',
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return (string)__($this->label);
    }

    public function getFromDate(): string
    {
        return date('Y-m-d', $this->fromTimestamp);
    }

    public function getToDate(): string
    {
        return date('Y-m-d', $this->toTimestamp);
    }

    public function getFromDay(): string
    {
        $formatter = $this->getDateFormatter();
        $formatter->setPattern('EEEE');

        return $formatter->format($this->fromTimestamp);
    }

    public function getToDay(): string
    {
        $formatter = $this->getDateFormatter();
        $formatter->setPattern('EEEE');

        return $formatter->format($this->toTimestamp);
    }

    public function getFromTime(): string
    {
        return date('H:i', $this->fromTimestamp);
    }

    public function getToTime(): string
    {
        return date('H:i', $this->toTimestamp);
    }

    public function getFromTimestamp(): int
    {
        return $this->fromTimestamp;
    }

    public function getToTimestamp(): int
    {
        return $this->toTimestamp;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    private function getDateFormatter(): IntlDateFormatter
    {
        $currentLocale = $this->localeResolver->getLocale();

        return datefmt_create(
            $currentLocale,
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            $this->timezone->getDefaultTimezone(),
        );
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'value' => $this->getValue(),
            'price' => $this->getPrice(),
            'fromDay' => $this->getFromDay(),
            'fromTime' => $this->getFromTime(),
            'toDay' => $this->getToDay(),
            'toTime' => $this->getToTime(),
            'disabled' => $this->isDisabled(),
            'carrier' => $this->getCarrier(),
            'carrierImage' => $this->getCarrierImage(),
        ];
    }

    public function getCarrier(): string
    {
        return $this->carrier;
    }

    public function getCarrierImage(): string
    {
        return $this->carrierImage;
    }

    public function getValue(): array
    {
        return $this->value;
    }
}
