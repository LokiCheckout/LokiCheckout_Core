<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Observer;

use LokiCheckout\Core\Observer\SaveAfterRepositoryDispatch;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\LayoutInterface;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class SaveAfterRepositoryDispatchTest extends TestCase
{
    public function testNoOpWhenLokiCheckoutHandleAbsent(): void
    {
        $layout = ObjectManager::getInstance()->create(LayoutInterface::class);
        $layout->getUpdate()->addHandle('cms_index_index');

        $observer = new Observer([
            'layout' => $layout,
            'updates' => ['field' => 'value'],
        ]);

        $subject = ObjectManager::getInstance()->get(SaveAfterRepositoryDispatch::class);
        $subject->execute($observer);

        $this->assertTrue(true);
    }

    public function testNoOpWhenLayoutMissing(): void
    {
        $observer = new Observer(['updates' => ['field' => 'value']]);

        $subject = ObjectManager::getInstance()->get(SaveAfterRepositoryDispatch::class);
        $subject->execute($observer);

        $this->assertTrue(true);
    }
}
