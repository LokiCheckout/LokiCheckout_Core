<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Controller\Index;

use LokiCheckout\Core\Controller\Index\Finalize;
use LokiCheckout\Core\Step\FinalStep\FinalizeToken;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Message\MessageInterface;
use Magento\TestFramework\Fixture\AppArea;
use Magento\TestFramework\Fixture\Config as ConfigFixture;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class FinalizeTest extends TestCase
{
    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testInvalidTokenRedirectsWithoutPlacingOrder(): void
    {
        $result = $this->executeWithToken('invalid-token');

        $this->assertInstanceOf(Redirect::class, $result);

        $messageManager = ObjectManager::getInstance()->get(ManagerInterface::class);
        $this->assertNotEmpty($messageManager->getMessages()->getItemsByType(MessageInterface::TYPE_ERROR));
    }

    #[ConfigFixture('loki_checkout/general/enable', 1, 'store', 'default')]
    public function testFormKeyIsNotAccepted(): void
    {
        $formKey = ObjectManager::getInstance()->get(\Magento\Framework\Data\Form\FormKey::class)->getFormKey();

        $request = ObjectManager::getInstance()->get(RequestInterface::class);
        $request->setParam('form_key', $formKey);

        $result = $this->executeWithToken('');

        $this->assertInstanceOf(Redirect::class, $result);

        $messageManager = ObjectManager::getInstance()->get(ManagerInterface::class);
        $this->assertNotEmpty($messageManager->getMessages()->getItemsByType(MessageInterface::TYPE_ERROR));
    }

    #[ConfigFixture('loki_checkout/general/enable', 0, 'store', 'default')]
    public function testDisabledCheckoutIsNotReachable(): void
    {
        $token = ObjectManager::getInstance()->get(FinalizeToken::class)->getToken();

        $this->assertInstanceOf(Forward::class, $this->executeWithToken($token));
    }

    private function executeWithToken(string $token): Redirect|Forward
    {
        $request = ObjectManager::getInstance()->get(RequestInterface::class);
        $request->setParam(FinalizeToken::PARAM_NAME, $token);

        $subject = ObjectManager::getInstance()->create(Finalize::class, ['request' => $request]);

        return $subject->execute();
    }
}
