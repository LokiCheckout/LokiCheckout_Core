<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Fixture;

use Magento\Checkout\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\GuestCartManagementInterface;
use Magento\Quote\Model\MaskedQuoteIdToQuoteId;

trait CartFixture
{
    use GetObjectManager;

    public function getGuestCart(): CartInterface
    {
        static $cart = null;
        if ($cart instanceof CartInterface) {
            return $cart;
        }

        $guestCartManagement = $this->getObjectManager()->get(GuestCartManagementInterface::class);
        $maskedQuoteIdToQuoteId = $this->getObjectManager()->get(MaskedQuoteIdToQuoteId::class);
        $cartRepository = $this->getObjectManager()->get(CartRepositoryInterface::class);

        $maskId = $guestCartManagement->createEmptyCart();
        $cartId = $maskedQuoteIdToQuoteId->execute($maskId);

        $checkoutSession = $this->getObjectManager()->get(Session::class);
        $checkoutSession->setQuoteId($cartId);

        return $cartRepository->get($cartId);
    }

    public function getCustomerCart(int $customerId): CartInterface
    {
        static $cart = null;
        if ($cart instanceof CartInterface) {
            return $cart;
        }

        $customerRepository = $this->getObjectManager()->get(CustomerRepositoryInterface::class);
        $customer = $customerRepository->getById($customerId);

        $cartManagement = $this->getObjectManager()->get(CartManagementInterface::class);
        $maskedQuoteIdToQuoteId = $this->getObjectManager()->get(MaskedQuoteIdToQuoteId::class);
        $cartRepository = $this->getObjectManager()->get(CartRepositoryInterface::class);

        $maskId = $cartManagement->createEmptyCartForCustomer($customerId);
        $cartId = $maskedQuoteIdToQuoteId->execute((string)$maskId);

        $checkoutSession = $this->getObjectManager()->get(Session::class);
        $checkoutSession->setQuoteId($cartId);
        $checkoutSession->setCustomerData($customer);

        return $cartRepository->get($cartId);
    }
}
