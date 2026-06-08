<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Step\FinalStep\Task;

use LokiCheckout\Core\Step\FinalStep\Task\SaveInAddressBookTask;
use LokiCheckout\Core\Test\Fixture\CustomerFixture;
use LokiCheckout\Core\Test\Fixture\CustomerLoggedInFixture;
use LokiCheckout\Core\Test\Integration\LokiCheckoutTestCase;
use Magento\Catalog\Test\Fixture\Product as ProductFixture;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Quote\Test\Fixture\AddProductToCart as AddProductToCartFixture;
use Magento\Quote\Test\Fixture\GuestCart as GuestCartFixture;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;

#[
    DataFixture(CustomerFixture::class, ['email' => 'savetask@example.com'], as: 'customer'),
    DataFixture(CustomerLoggedInFixture::class, ['customer_id' => '$customer.id$']),
    DataFixture(ProductFixture::class, as: 'product'),
    DataFixture(GuestCartFixture::class, as: 'cart'),
    DataFixture(AddProductToCartFixture::class, ['cart_id' => '$cart.id$', 'product_id' => '$product.id$']),
    AppArea('frontend'),
]
class SaveInAddressBookTaskTest extends LokiCheckoutTestCase
{
    public function testBeforePlaceOrderCreatesCustomerAddress(): void
    {
        $cart = $this->getCart();
        $customer = $this->getObjectManager()->get(CustomerRepositoryInterface::class)
            ->get('savetask@example.com');
        $cart->setCustomerId($customer->getId());
        $cart->setCustomer($customer);

        $shippingAddress = $cart->getShippingAddress();
        $shippingAddress->addData([
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'street' => ['Amalialaan 126'],
            'city' => 'Baarn',
            'postcode' => '3743KJ',
            'country_id' => 'NL',
            'telephone' => '0123456789',
        ]);
        $shippingAddress->setSameAsBilling(true);
        $shippingAddress->setSaveInAddressBook(true);
        $shippingAddress->setCustomerAddressId(null);

        $subject = $this->getObjectManager()->get(SaveInAddressBookTask::class);
        $subject->beforePlaceOrder($cart);

        $this->assertNotEmpty($shippingAddress->getCustomerAddressId());

        $reloadedCustomer = $this->getObjectManager()->get(CustomerRepositoryInterface::class)
            ->get('savetask@example.com');
        $this->assertNotEmpty($reloadedCustomer->getAddresses());
    }
}
