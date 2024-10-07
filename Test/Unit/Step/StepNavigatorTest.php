<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Test\Unit\Step;

use Magento\Checkout\Model\Session;
use Magento\Framework\View\LayoutInterface;
use PHPUnit\Framework\TestCase;
use Yireo\FriggaCheckout\Step\StepInterface;
use Yireo\FriggaCheckout\Step\StepNavigator;

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
            $this->createMock(LayoutInterface::class),
            $this->createMock(Session::class),
        );
    }

    private function getStep(string $stepCode): StepInterface
    {
        $step = $this->createMock(StepInterface::class);
        $step->method('getCode')->willReturn($stepCode);
        return $step;
    }
}
