<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util;

use LokiCheckout\Core\Util\LoginCustomer;
use Magento\Customer\Model\Session;
use Magento\Customer\Test\Fixture\Customer as CustomerFixture;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Message\MessageInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\DataFixture;
use Magento\TestFramework\Fixture\DbIsolation;
use PHPUnit\Framework\TestCase;

#[
    DbIsolation(true),
    AppArea('frontend'),
    DataFixture(
        CustomerFixture::class,
        ['email' => 'login.customer@example.com', 'password' => 'Password123!'],
        'customer'
    ),
]
class LoginCustomerTest extends TestCase
{
    protected function tearDown(): void
    {
        ObjectManager::getInstance()->get(Session::class)->logout();
    }

    public function testLoginWithValidCredentials(): void
    {
        $subject = ObjectManager::getInstance()->get(LoginCustomer::class);
        $subject->execute('login.customer@example.com', 'Password123!');

        $session = ObjectManager::getInstance()->get(Session::class);
        $this->assertTrue($session->isLoggedIn());
        $this->assertSame('login.customer@example.com', $session->getCustomer()->getEmail());

        $this->assertHasMessageOfType(MessageInterface::TYPE_SUCCESS);
    }

    public function testLoginWithInvalidCredentials(): void
    {
        $subject = ObjectManager::getInstance()->get(LoginCustomer::class);
        $subject->execute('login.customer@example.com', 'WrongPassword!');

        $session = ObjectManager::getInstance()->get(Session::class);
        $this->assertFalse($session->isLoggedIn());

        $this->assertHasMessageOfType(MessageInterface::TYPE_ERROR);

        $messageManager = ObjectManager::getInstance()->get(ManagerInterface::class);
        foreach ($messageManager->getMessages()->getItemsByType(MessageInterface::TYPE_ERROR) as $message) {
            $this->assertStringNotContainsString('Invalid login or password', (string)$message->getText());
        }
    }

    private function assertHasMessageOfType(string $type): void
    {
        $messageManager = ObjectManager::getInstance()->get(ManagerInterface::class);
        $messages = $messageManager->getMessages()->getItemsByType($type);
        $this->assertNotEmpty($messages);
    }
}
