<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Payment\Vault;

use LokiCheckout\Core\Payment\Vault\NormalizedPaymentToken;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Model\PaymentTokenFactory;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class NormalizedPaymentTokenTest extends TestCase
{
    public function testGettersExposeTokenDetails(): void
    {
        $token = $this->createToken('abc123hash', [
            'type' => 'VI',
            'name' => 'Visa',
            'maskedCC' => '1111',
        ]);

        $normalized = ObjectManager::getInstance()->create(
            NormalizedPaymentToken::class,
            ['paymentToken' => $token]
        );

        $this->assertSame('abc123hash', $normalized->getPublicHash());
        $this->assertSame('VI', $normalized->getType());
        $this->assertSame('Visa', $normalized->getName());
        $this->assertSame('1111', $normalized->getHint());
    }

    public function testMissingDetailsFallBackToEmptyString(): void
    {
        $token = $this->createToken('hash2', []);

        $normalized = ObjectManager::getInstance()->create(
            NormalizedPaymentToken::class,
            ['paymentToken' => $token]
        );

        $this->assertSame('', $normalized->getType());
        $this->assertSame('', $normalized->getName());
        $this->assertSame('', $normalized->getHint());
    }

    private function createToken(string $publicHash, array $details): PaymentTokenInterface
    {
        $factory = ObjectManager::getInstance()->get(PaymentTokenFactory::class);
        $token = $factory->create(PaymentTokenFactory::TOKEN_TYPE_CREDIT_CARD);
        $token->setPublicHash($publicHash);
        $token->setTokenDetails(json_encode($details));

        return $token;
    }
}
