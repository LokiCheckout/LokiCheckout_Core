<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Validator\Component;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\AccountManagement;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config;
use Yireo\LokiCheckout\Magewire\Checkout\Quote\CustomerEmail;
use Yireo\LokiCheckout\Validator\Component\EmailValidator;
use Yireo\LokiCheckout\ViewModel\State\ShippingAddressState;
use Yireo\MagewireTestUtils\Test\Integration\MagewireComponentTestCase;

#[AppArea('frontend')]
class EmailValidatorTest extends MagewireComponentTestCase
{
    public function testWithNoValue(): void
    {
        $validator = ObjectManager::getInstance()->get(EmailValidator::class);
        $component = ObjectManager::getInstance()->get(CustomerEmail::class);

        $this->assertFalse($validator->validate($component));
    }

    /**
     * @param string $postcode
     * @param string $countryId
     * @param bool $return
     * @return void
     * @dataProvider getValues
     */
    public function testWithVariousValues(string $email, bool $return): void
    {
        $validator = ObjectManager::getInstance()->get(EmailValidator::class);
        $component = ObjectManager::getInstance()->get(CustomerEmail::class);
        $component->value = $email;

        $this->assertSame($return, $validator->validate($component));
    }

    public function getValues(): array
    {
        return [
            ['jane@example.com', true],
            ['jane@example', false],
            ['jane', false],
            ['jane@example.comcomcomcomcom', false],
        ];
    }

    #[Config(AccountManagement::GUEST_CHECKOUT_LOGIN_OPTION_SYS_CONFIG, 1, 'website')]
    public function testWithExistingEmail(): void
    {
        $this->createCustomer('john@example.com');

        $validator = ObjectManager::getInstance()->get(EmailValidator::class);
        $component = ObjectManager::getInstance()->get(CustomerEmail::class);
        $component->value = 'john@example.com';

        $this->assertFalse($validator->validate($component));
        $this->assertMagewireComponentHasError($component, 'This email address is not available');
    }

    private function createCustomer(string $email)
    {
        $customerRepository = ObjectManager::getInstance()->get(CustomerRepositoryInterface::class);
        try {
            $customerRepository->get($email);
            return;
        } catch (NoSuchEntityException $e) {
        } catch (LocalizedException $e) {
        }

        $customerFactory = ObjectManager::getInstance()->get(CustomerInterfaceFactory::class);
        $customer = $customerFactory->create();
        $customer->setFirstname('John');
        $customer->setLastname('Doe');
        $customer->setEmail($email);
        $customerRepository->save($customer);
    }
}
