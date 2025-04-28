<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Trait;

use Magento\Checkout\Api\Data\TotalsInformationInterfaceFactory;
use Magento\Checkout\Api\TotalsInformationManagementInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Model\Quote;

trait AssertTotalsContainShippingMethod
{
    protected function assertTotalsContainShippingMethod(string $shippingMethodCode, Quote $quote): void
    {
        $shippingMethodLabel = '';
        foreach ($this->getRepository()->getShippingMethods() as $shippingMethod) {
            if (str_starts_with($shippingMethodCode, $shippingMethod->getMethodCode() . '_')) {
                $shippingMethodLabel = $shippingMethod->getMethodTitle();
            }
        }

        $totalsInformationFactory = ObjectManager::getInstance()->get(TotalsInformationInterfaceFactory::class);
        $totalsInformation = $totalsInformationFactory->create();
        $totalsInformation->setAddress($quote->getShippingAddress());

        $totalsInformationManagement = ObjectManager::getInstance()->get(TotalsInformationManagementInterface::class);
        $totals = $totalsInformationManagement->calculate($quote->getId(), $totalsInformation);

        $totalSegments = $totals->getTotalSegments();
        $totalSegmentCodes = [];
        $totalSegmentTitles = [];
        $shippingMethodLabelMatched = false;
        foreach ($totalSegments as $totalSegment) {
            $totalSegmentCodes[] = $totalSegment->getCode();
            $totalSegmentTitles[] = $totalSegment->getTitle();
            if (str_contains($totalSegment->getTitle(), $shippingMethodLabel)) {
                $shippingMethodLabelMatched = true;
            }
        }

        $this->assertNotEmpty($totalSegments);
        $this->assertContains('shipping', $totalSegmentCodes);
        $this->assertTrue($shippingMethodLabelMatched, var_export($totalSegmentTitles, true));
    }
}
