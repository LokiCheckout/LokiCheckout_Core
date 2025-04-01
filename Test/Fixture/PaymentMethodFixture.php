<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Fixture;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\DataObject;
use Magento\Quote\Model\Quote;
use Magento\TestFramework\Fixture\DataFixtureInterface;

class PaymentMethodFixture implements DataFixtureInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
    ) {
    }

    public function apply(array $data = []): ?DataObject
    {
        $paymentMethod = $data['payment_method'];

        /** @var Quote $quote */
        $quote = $this->cartRepository->get($data['cart_id']);
        $quote->setPaymentMethod($paymentMethod);
        $quote->getPayment()->importData(['method' => $paymentMethod]);
        $this->cartRepository->save($quote);

        return null;
    }
}
