<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Fixture;

use Magento\Checkout\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\MaskedQuoteIdToQuoteId;

trait CartFixture
{
    /**
     * This requires the data fixture Magento/Checkout/_files/quote_with_simple_products.php
     *
     * @return CartInterface
     * @deprecated Use PHP Attributes instead
     */
    public function getGuestCart(): CartInterface
    {
        $quoteRepository = ObjectManager::getInstance()->get(CartRepositoryInterface::class);
        $searchCriteriaBuilder = ObjectManager::getInstance()->get(SearchCriteriaBuilder::class);
        $searchCriteriaBuilder->addFilter('reserved_order_id', 'test_quote_with_simple_products');
        $searchCriteriaBuilder->setPageSize(1);
        $searchResults = $quoteRepository->getList($searchCriteriaBuilder->create());
        if ($searchResults->getTotalCount() === 0) {
            throw new \RuntimeException('Quote is empty');
        }

        $cart = $searchResults->getItems()[0];

        $checkoutSession = $this->getObjectManager()->get(Session::class);
        $checkoutSession->setQuoteId($cart->getId());

        return $cart;
    }

    /*
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
    */
}
