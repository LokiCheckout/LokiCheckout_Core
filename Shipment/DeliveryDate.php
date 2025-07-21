<?php declare(strict_types=1);

namespace LokiCheckout\Core\Shipment;

use IntlDateFormatter;
use Magento\Framework\Locale\ResolverInterface as LocaleResolver;
use Magento\Framework\Stdlib\DateTime\Timezone;
use LokiCheckout\Core\Shipment\DeliveryDate\Timeframe;

class DeliveryDate
{
    public function __construct(
        private readonly LocaleResolver $localeResolver,
        private readonly Timezone $timezone,
        private readonly int $timestamp,
        private array $timeframes = [],
        private readonly string $datePattern = 'EEEE, MMMM dd', // @doc
    ) {
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    public function getDate(): string
    {
        $formatter = $this->getDateFormatter();
        $formatter->setPattern($this->datePattern);
        return $formatter->format($this->getTimestamp());
    }

    public function getTimeframes(): array
    {
        return $this->timeframes;
    }

    public function addTimeframe(Timeframe $timeframe): void
    {
        $this->timeframes[] = $timeframe;
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
