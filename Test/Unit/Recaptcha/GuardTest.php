<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\Recaptcha;

use LokiCheckout\Core\Recaptcha\Config;
use LokiCheckout\Core\Recaptcha\FormId;
use LokiCheckout\Core\Recaptcha\Guard;
use LokiCheckout\Core\Test\Unit\Stub\CheckoutSessionDataStub;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class GuardTest extends TestCase
{
    public function testAssertPassedWhenCaptchaIsNotConfigured(): void
    {
        $guard = $this->createGuard(false);

        $this->assertTrue($guard->assertPassed(FormId::PLACE_ORDER));
    }

    public function testAssertPassedFailsWithoutAPass(): void
    {
        $guard = $this->createGuard(true);

        $this->assertFalse($guard->assertPassed(FormId::PLACE_ORDER));
    }

    public function testAssertPassedSucceedsAfterMarkingPassed(): void
    {
        $guard = $this->createGuard(true);
        $guard->markPassed(FormId::PLACE_ORDER);

        $this->assertTrue($guard->assertPassed(FormId::PLACE_ORDER));
    }

    public function testAPassCanOnlyBeUsedOnce(): void
    {
        $guard = $this->createGuard(true);
        $guard->markPassed(FormId::PLACE_ORDER);

        $this->assertTrue($guard->assertPassed(FormId::PLACE_ORDER));
        $this->assertFalse($guard->assertPassed(FormId::PLACE_ORDER));
    }

    public function testPassesAreScopedPerFormId(): void
    {
        $guard = $this->createGuard(true);
        $guard->markPassed(FormId::CUSTOMER_LOGIN);

        $this->assertFalse($guard->assertPassed(FormId::PLACE_ORDER));
        $this->assertTrue($guard->assertPassed(FormId::CUSTOMER_LOGIN));
    }

    public function testAnExpiredPassIsRejected(): void
    {
        $session = CheckoutSessionDataStub::create();
        $session->setData('loki_recaptcha_passed', [FormId::PLACE_ORDER => time() - 3600]);

        $guard = new Guard($this->createConfig(true), $session);

        $this->assertFalse($guard->assertPassed(FormId::PLACE_ORDER));
    }

    private function createGuard(bool $enabled): Guard
    {
        return new Guard($this->createConfig($enabled), CheckoutSessionDataStub::create());
    }

    private function createConfig(bool $enabled): Config&MockObject
    {
        $config = $this->createMock(Config::class);
        $config->method('isEnabledFor')->willReturn($enabled);

        return $config;
    }
}
