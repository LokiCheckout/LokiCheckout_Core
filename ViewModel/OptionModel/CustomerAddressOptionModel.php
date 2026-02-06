<?php declare(strict_types=1);

namespace LokiCheckout\Core\ViewModel\OptionModel;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use LokiCheckout\Core\Component\Checkout\Address\Select\SelectViewModel;
use Loki\Components\Component\ComponentViewModelInterface;
use Loki\Field\Util\OptionModelInterface;

class CustomerAddressOptionModel implements ArgumentInterface, OptionModelInterface
{
    public function __construct(
        private readonly CheckoutState $checkoutState
    ) {
    }

    public function getOptions(ComponentViewModelInterface $viewModel): array
    {
        if (false === $this->checkoutState->getCustomerSession()->isLoggedIn()) {
            return [];
        }

        $block = $viewModel->getBlock();
        $showEmptySelectOption = (bool)$block->getShowEmptySelectOption(); // @doc
        $emptySelectOptionLabel = (string)$block->getEmptySelectOptionLabel(); // @doc

        /** @var SelectViewModel $viewModel */
        $options = [];
        if ($showEmptySelectOption) {
            $options[0] = $emptySelectOptionLabel;
        }

        foreach ($viewModel->getAddressLines() as $addressLine) {
            $options[] = $addressLine;
        }

        return $options;
    }
}
