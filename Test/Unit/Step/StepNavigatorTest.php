<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Step;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Checkout\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Message\ManagerInterface;
use PHPUnit\Framework\TestCase;
use LokiCheckout\Core\Component\Checkout\Step\StepViewModelInterface;
use LokiCheckout\Core\Config\Config;
use LokiCheckout\Core\Config\XmlConfig;
use LokiCheckout\Core\Step\FinalStep;
use LokiCheckout\Core\Step\StepNavigator;
use Loki\Components\Component\ComponentRegistry;

class StepNavigatorTest extends TestCase
{
    public function testGetFirstStep()
    {
        $stepNavigator = $this->getStepNavigator();

        $step1 = $this->getStep('step1');
        $stepNavigator->addStep($step1);

        $step2 = $this->getStep('step2');
        $stepNavigator->addStep($step2);

        $this->assertInstanceOf($step1::class, $stepNavigator->getFirstStep());
    }

    public function testGetLastStep()
    {
        $stepNavigator = $this->getStepNavigator();

        $step1 = $this->getStep('step1');
        $stepNavigator->addStep($step1);

        $step2 = $this->getStep('step2');
        $stepNavigator->addStep($step2);

        $this->assertInstanceOf($step2::class, $stepNavigator->getLastStep());
    }

    public function testGetPreviousStep()
    {
        $stepNavigator = $this->getStepNavigator();

        $step1 = $this->getStep('step1');
        $stepNavigator->addStep($step1);

        $step2 = $this->getStep('step2');
        $stepNavigator->addStep($step2);

        $this->assertEquals(2, count($stepNavigator->getSteps()));
        $stepNavigator->setCurrentStepByCode('step2');

        $this->assertInstanceOf($step1::class, $stepNavigator->getPreviousStep($step2));
    }

    public function testGetNextStep()
    {
        $stepNavigator = $this->getStepNavigator();

        $step1 = $this->getStep('step1');
        $stepNavigator->addStep($step1);

        $step2 = $this->getStep('step2');
        $stepNavigator->addStep($step2);

        $this->assertEquals(2, count($stepNavigator->getSteps()));
        $stepNavigator->setCurrentStepByCode('step1');

        $this->assertInstanceOf($step2::class, $stepNavigator->getNextStep($step1));
    }

    private function getStepNavigator():StepNavigator
    {
        return new StepNavigator(
            $this->createMock(XmlConfig::class),
            $this->createMock(Config::class),
            $this->createMock(CheckoutState::class),
            $this->createMock(FinalStep::class),
            $this->createMock(ManagerInterface::class),
            $this->createMock(ComponentRegistry::class),
            $this->createMock(RequestInterface::class),
        );
    }

    private function getStep(string $stepCode): StepViewModelInterface
    {
        $step = $this->createMock(StepViewModelInterface::class);
        $step->method('getCode')->willReturn($stepCode);
        return $step;
    }
}
