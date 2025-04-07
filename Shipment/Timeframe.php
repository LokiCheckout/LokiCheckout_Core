<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Shipment;

use IntlDateFormatter;
use Magento\Framework\Locale\ResolverInterface as LocaleResolver;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Timeframe
{
    public function __construct(
        private LocaleResolver $localeResolver,
        private TimezoneInterface $timezone,
        private int $fromTimestamp,
        private int $toTimestamp,
        private string $label,
        private float $price = 0
    ) {
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

    public function getLabel(): string
    {
        return $this->label;
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
}
