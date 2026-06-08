<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\ViewModel;

use LokiCheckout\Core\ViewModel\CssVariables;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class CssVariablesTest extends TestCase
{
    private function getSubject(): CssVariables
    {
        return ObjectManager::getInstance()->create(CssVariables::class);
    }

    public function testSetAndGet(): void
    {
        $subject = $this->getSubject();
        $subject->set('--primary-color', '#ff0000');
        $subject->set('--secondary-color', '#00ff00');

        $variables = $subject->get();
        $this->assertCount(2, $variables);
    }

    public function testSetDeduplicatesByName(): void
    {
        $subject = $this->getSubject();
        $subject->set('--primary-color', '#ff0000');
        $subject->set('--primary-color', '#0000ff');

        $variables = $subject->get();
        $this->assertCount(1, $variables);
        $this->assertSame('#0000ff', $variables['--primary-color']->getValue());
    }
}
