<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Magewire\Checkout;

use Exception;
use Magewirephp\Magewire\Component;
use Yireo\FriggaCheckout\Magewire\Generic\ButtonInterface;
use Yireo\FriggaCheckout\Step\StepNavigator;

class StepForwardButton extends Component implements ButtonInterface
{
    public function __construct(
        private StepNavigator $stepNavigator,
    ) {
    }

    public function execute()
    {
        try {
            $result = $this->stepNavigator->setNextStep($this);
        } catch (Exception $e) {
            $this->dispatchErrorMessage($e->getMessage());
            return;
        }

        if (true === $result) {
            $this->emit('stepChange', $this->stepNavigator->getCurrentStep()->getCode());
        }
    }

    public function allow(): bool
    {
        return true;
    }
}
