<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Observer;

use LokiCheckout\Core\Observer\AssignAdditionalBlockVariables;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Element\Template;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class AssignAdditionalBlockVariablesTest extends TestCase
{
    public function testComponentNameIsAssignedForLokiCheckoutBlock(): void
    {
        $block = ObjectManager::getInstance()->create(Template::class);
        $block->setNameInLayout('loki-checkout.shipping.address.firstname');
        $block->setJsId('shipping_address_firstname');

        $this->assertEmpty($block->getComponentName());

        $this->executeObserver($block);

        $this->assertNotEmpty($block->getComponentName());
    }

    public function testNonLokiBlockIsIgnored(): void
    {
        $block = ObjectManager::getInstance()->create(Template::class);
        $block->setNameInLayout('some.other.block');
        $block->setJsId('other_block');

        $this->executeObserver($block);

        $this->assertEmpty($block->getComponentName());
    }

    private function executeObserver(Template $block): void
    {
        $event = new Event(['block' => $block]);
        $observer = new Observer(['event' => $event]);

        $subject = ObjectManager::getInstance()->get(AssignAdditionalBlockVariables::class);
        $subject->execute($observer);
    }
}
