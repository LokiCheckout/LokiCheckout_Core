<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Fixture;

use LokiCheckout\Core\ViewModel\CheckoutState;
use Magento\Framework\DataObject;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\TestFramework\Fixture\DataFixtureInterface;

class CheckoutStateFixture implements DataFixtureInterface
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepository,
        private readonly CheckoutState $checkoutState,
    ) {
    }

    public function apply(array $data = []): ?DataObject
    {
        /** @var Quote $quote */
        $quote = $this->cartRepository->get($data['cart_id']);

        $this->checkoutState->setQuote($quote);
        $this->checkoutState->setQuoteId($quote->getId());

        return null;
    }
}
