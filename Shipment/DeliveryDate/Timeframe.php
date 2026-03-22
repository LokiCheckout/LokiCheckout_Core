<?php declare(strict_types=1);

namespace LokiCheckout\Core\Shipment\DeliveryDate;

use IntlDateFormatter;
use JsonSerializable;
use Magento\Framework\Locale\ResolverInterface as LocaleResolver;
use Magento\Framework\Stdlib\DateTime\Timezone;

class Timeframe implements JsonSerializable
{
    public function __construct(
        private readonly LocaleResolver $localeResolver,
        private readonly Timezone $timezone,
        private readonly string $code,
        private readonly string $id,
        private readonly string $label,
        private readonly int $fromTimestamp,
        private readonly int $toTimestamp,
        private readonly float $price = 0
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }
    public function getLabel(): string
    {
        return $this->label;
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

    public function jsonSerialize(): mixed
    {
        return [
            'code' => $this->getCode(),
            'id' => $this->getId(),
            'label' => $this->getLabel(),
            'price' => $this->getPrice(),
            'fromTimestamp' => $this->getFromTimestamp(),
            'fromDay' => $this->getFromDay(),
            'fromTime' => $this->getFromTime(),
            'toTimestamp' => $this->getToTimestamp(),
            'toDay' => $this->getToDay(),
            'toTime' => $this->getToTime(),
        ];
    }
}
