<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Trait;

use Magento\Framework\App\ObjectManager;

trait AssertCarrierIsActive
{
    protected function assertCarrierIsActive(string $carrierCode): void
    {
        $shippingConfig = ObjectManager::getInstance()->get(\Magento\Shipping\Model\Config::class);
        $activeCarriers = $shippingConfig->getActiveCarriers();
        $activeCarrierCodes = [];
        foreach ($activeCarriers as $activeCarrier) {
            $activeCarrierCodes[] = $activeCarrier->getCarrierCode();
        }

        $this->assertContains(
            $carrierCode,
            $activeCarrierCodes,
            'Active carriers: ' . implode(', ', $activeCarrierCodes)
        );
    }
}
