<?php declare(strict_types=1);

namespace Yireo\FriggaCheckout\Test\Integration\Magewire\Checkout\ShippingStep\ShippingAddress;

use Magento\TestFramework\Fixture\AppArea;
use Yireo\FriggaCheckout\Magewire\Checkout\ShippingStep\ShippingAddress\Firstname;
use Yireo\FriggaCheckout\Test\Integration\Fixture\CartFixture;
use Yireo\FriggaCheckout\Test\Integration\Fixture\ProductFixture;
use Yireo\FriggaCheckout\Test\Integration\FriggaTestCase;

#[AppArea('frontend')]
class FirstnameTest extends FriggaTestCase
{
    const BLOCK_NAME = 'frigga.checkout.shipping-step.shipping-address.firstname';

    use ProductFixture;
    use CartFixture;

    public function testComponentIsPresentOnPage()
    {
        /*
        $fulltextMock = $this->createMock(\Magento\CatalogSearch\Model\Indexer\Fulltext::class);
        \Magento\TestFramework\ObjectManager::getInstance()->addSharedInstance($fulltextMock, \Magento\CatalogSearch\Model\Indexer\Fulltext::class);
        */

        $cart = $this->getGuestCart();
        $product = $this->getProduct();
        $cart->addProduct($product, 1);

        //$store = $this->storeManager->getStore();
        //$quote->setStore($store);
        //$quote->assignCustomer($customer);

        //$firstname = $this->getComponentByBlockName('firstname');
        //$firstname->updated('John', 'value');

        $this->dispatchToCart();
        $body = $this->getResponse()->getBody();
        $this->assertMagewireBlockExistsInHtml(self::BLOCK_NAME, $body);
        $this->assertMagewireComponentResolves(self::BLOCK_NAME, Firstname::class, ['checkout_index_index']);

    }
}
