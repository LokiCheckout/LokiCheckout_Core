<?php declare(strict_types=1);

namespace LokiCheckout\Core\Shipment;

use IntlDateFormatter;
use JsonSerializable;
use Magento\Framework\Locale\ResolverInterface as LocaleResolver;
use Magento\Framework\Stdlib\DateTime\Timezone;
use LokiCheckout\Core\Shipment\DeliveryDate\Timeframe;

class DeliveryDate implements JsonSerializable
{
    public function __construct(
        private readonly LocaleResolver $localeResolver,
        private readonly Timezone $timezone,
        private readonly string $id,
        private readonly int $timestamp,
        private array $timeframes = [],
        private readonly string $datePattern = 'EEEE, MMMM dd', // @doc
    ) {
    }

    public function getId(): string
    {
        if (!empty($this->id)) {
            return $this->id;
        }

        return (string)$this->getTimestamp();
    }

    public function getDate(): string
    {
        $formatter = $this->getDateFormatter();
        $formatter->setPattern($this->datePattern);
        return $formatter->format($this->getTimestamp());
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }

    /**
     * @return Timeframe[]
     */
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

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'date' => $this->getDate(),
            'timestamp' => $this->getTimestamp(),
            'timeframes' => $this->getTimeframes(),
        ];
    }
}
