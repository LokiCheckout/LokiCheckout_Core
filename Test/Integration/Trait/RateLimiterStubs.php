<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Trait;

use Magento\Checkout\Api\Exception\PaymentProcessingRateLimitExceededException;
use Magento\Checkout\Api\PaymentProcessingRateLimiterInterface;
use Magento\Checkout\Api\PaymentSavingRateLimiterInterface;
use Magento\Framework\ObjectManager\ConfigInterface;
use Magento\TestFramework\ObjectManager;

trait RateLimiterStubs
{
    private object $processingRateLimiter;
    private object $savingRateLimiter;

    private function installRateLimiterStubs(): void
    {
        $this->processingRateLimiter = new class implements PaymentProcessingRateLimiterInterface {
            public int $calls = 0;
            public bool $exceeded = false;

            public function limit(): void
            {
                $this->calls++;
                if ($this->exceeded) {
                    throw new PaymentProcessingRateLimitExceededException(__('Rate limit exceeded'));
                }
            }
        };

        $this->savingRateLimiter = new class implements PaymentSavingRateLimiterInterface {
            public int $calls = 0;
            public bool $exceeded = false;

            public function limit(): void
            {
                $this->calls++;
                if ($this->exceeded) {
                    throw new PaymentProcessingRateLimitExceededException(__('Rate limit exceeded'));
                }
            }
        };

        foreach ($this->getRateLimiterTypes() as $type => $instance) {
            ObjectManager::getInstance()->addSharedInstance($instance, $type);
        }
    }

    private function removeRateLimiterStubs(): void
    {
        foreach (array_keys($this->getRateLimiterTypes()) as $type) {
            ObjectManager::getInstance()->removeSharedInstance($type);
        }
    }

    private function getRateLimiterTypes(): array
    {
        $config = ObjectManager::getInstance()->get(ConfigInterface::class);
        $types = [];
        foreach ([
            PaymentProcessingRateLimiterInterface::class => $this->processingRateLimiter,
            PaymentSavingRateLimiterInterface::class => $this->savingRateLimiter,
        ] as $interface => $instance) {
            $types[$interface] = $instance;
            $types[$config->getPreference($interface)] = $instance;
        }

        return $types;
    }
}
