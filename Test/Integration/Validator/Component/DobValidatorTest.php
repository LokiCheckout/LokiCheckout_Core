<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Validator\Component;

use LokiCheckout\Core\Validator\Component\DobValidator;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;

#[AppArea('frontend')]
class DobValidatorTest extends TestCase
{
    private DobValidator $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(DobValidator::class);
    }

    public function testEmptyValueIsValid(): void
    {
        $this->assertTrue($this->subject->validate(''));
        $this->assertTrue($this->subject->validate(null));
    }

    public function testValidAdultDob(): void
    {
        $this->assertTrue($this->subject->validate('1980-01-02'));
    }

    public function testMalformedDateIsInvalid(): void
    {
        $result = $this->subject->validate('not-a-date');
        $this->assertIsArray($result);
    }

    public function testFutureDateIsInvalid(): void
    {
        $future = ((int)date('Y') + 5) . '-01-02';
        $result = $this->subject->validate($future);
        $this->assertIsArray($result);
    }

    public function testTooYoungIsInvalid(): void
    {
        $recent = ((int)date('Y') - 1) . '-01-02';
        $result = $this->subject->validate($recent);
        $this->assertIsArray($result);
        $this->assertStringContainsString('at least 10 years old', (string)$result[0]);
    }

    public function testDefaultMinimumAndMaximumAge(): void
    {
        $this->assertSame(10, $this->subject->getMinimumAge());
        $this->assertSame(150, $this->subject->getMaximumAge());
    }
}
