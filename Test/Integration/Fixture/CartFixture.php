<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Test\Integration\Fixture;

use Magento\Checkout\Model\Session;
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

        $productHelper = $this->getObjectManager()->get(\Magento\Catalog\Helper\Product::class);
        $productHelper->setSkipSaleableCheck(true);

        $checkoutSession = $this->getObjectManager()->get(Session::class);
        $checkoutSession->setQuoteId($cartId);

        return $cartRepository->get($cartId);
    }
}
