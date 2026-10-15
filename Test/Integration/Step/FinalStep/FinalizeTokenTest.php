<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Step\FinalStep;

use LokiCheckout\Core\Step\FinalStep\FinalizeToken;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class FinalizeTokenTest extends TestCase
{
    public function testTokenIsStableUntilUsed(): void
    {
        $finalizeToken = ObjectManager::getInstance()->get(FinalizeToken::class);
        $token = $finalizeToken->getToken();

        $this->assertSame(64, strlen($token));
        $this->assertSame($token, $finalizeToken->getToken());
        $this->assertStringContainsString(FinalizeToken::PARAM_NAME . '=' . $token, $finalizeToken->getUrl());
    }

    public function testTokenIsSingleUse(): void
    {
        $finalizeToken = ObjectManager::getInstance()->get(FinalizeToken::class);
        $token = $finalizeToken->getToken();

        $this->assertTrue($finalizeToken->validate($token));
        $this->assertFalse($finalizeToken->validate($token));
        $this->assertNotSame($token, $finalizeToken->getToken());
    }

    public function testInvalidTokenIsRejected(): void
    {
        $finalizeToken = ObjectManager::getInstance()->get(FinalizeToken::class);
        $token = $finalizeToken->getToken();

        $this->assertFalse($finalizeToken->validate(''));
        $this->assertFalse($finalizeToken->validate('wrong'));
        $this->assertTrue($finalizeToken->validate($token));
    }
}
