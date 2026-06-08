<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Plugin;

use LokiCheckout\Core\Plugin\ClearHelperDataPlugin;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class ClearHelperDataPluginTest extends TestCase
{
    private CheckoutSession $session;
    private ClearHelperDataPlugin $subject;

    protected function setUp(): void
    {
        $this->session = ObjectManager::getInstance()->get(CheckoutSession::class);
        $this->subject = ObjectManager::getInstance()->get(ClearHelperDataPlugin::class);
    }

    private function seedSession(): void
    {
        $this->session->setCurrentStep('shipping');
        $this->session->setCustomerAccountType('guest');
        $this->session->setAgreementIds([1, 2]);
    }

    private function assertLokiSessionCleared(): void
    {
        $this->assertNull($this->session->getCurrentStep());
        $this->assertNull($this->session->getCustomerAccountType());
        $this->assertNull($this->session->getAgreementIds());
    }

    public function testAfterClearQuoteResetsLokiKeys(): void
    {
        $this->seedSession();
        $result = $this->subject->afterClearQuote($this->session);

        $this->assertSame($this->session, $result);
        $this->assertLokiSessionCleared();
    }

    public function testAfterReplaceQuoteResetsLokiKeys(): void
    {
        $this->seedSession();
        $this->subject->afterReplaceQuote($this->session);

        $this->assertLokiSessionCleared();
    }

    public function testAfterClearHelperDataResetsLokiKeys(): void
    {
        $this->seedSession();
        $this->subject->afterClearHelperData($this->session);

        $this->assertLokiSessionCleared();
    }
}
