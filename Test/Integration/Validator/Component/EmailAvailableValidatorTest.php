<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator\Component;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\AccountManagement;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use PHPUnit\Framework\TestCase;
use LokiCheckout\Core\Validator\Component\EmailAvailableValidator;

#[AppArea('frontend')]
class EmailAvailableValidatorTest extends TestCase
{
    public function testWithNoValue(): void
    {
        $validator = ObjectManager::getInstance()->get(EmailAvailableValidator::class);
        $this->assertTrue($validator->validate(null));
    }

    #[ConfigFixture(AccountManagement::GUEST_CHECKOUT_LOGIN_OPTION_SYS_CONFIG, 1, 'website')]
    public function testWithExistingEmail(): void
    {
        $this->createCustomer('john@example.com');

        $validator = ObjectManager::getInstance()->get(EmailAvailableValidator::class);
        $email = 'john@example.com';
        $result = $validator->validate($email);

        $this->assertNotTrue($result);
        $this->assertIsArray($result);
        $this->assertTrue(count($result) === 1);
        $this->assertStringContainsString('is not available', $result[0]);
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
