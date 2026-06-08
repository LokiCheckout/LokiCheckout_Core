<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Integration\Util\Component;

use LokiCheckout\Core\Util\Component\AttributeProvider;
use Magento\Eav\Model\Entity\Attribute\AbstractAttribute;
use Magento\Framework\App\ObjectManager;
use Magento\TestFramework\Fixture\AppArea;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[AppArea('frontend')]
class AttributeProviderTest extends TestCase
{
    private AttributeProvider $subject;

    protected function setUp(): void
    {
        $this->subject = ObjectManager::getInstance()->get(AttributeProvider::class);
    }

    public function testGetAttribute(): void
    {
        $attribute = $this->subject->getAttribute('street');

        $this->assertInstanceOf(AbstractAttribute::class, $attribute);
        $this->assertSame('street', $attribute->getAttributeCode());
    }

    public function testGetAttributeLabel(): void
    {
        $label = $this->subject->getAttributeLabel('street');

        $this->assertNotSame('', $label);
    }

    public function testGetAttributeWithEmptyFieldThrows(): void
    {
        $this->expectException(RuntimeException::class);
        $this->subject->getAttribute('');
    }

    public function testGetAttributeWithZeroFieldThrows(): void
    {
        $this->expectException(RuntimeException::class);
        $this->subject->getAttribute('0');
    }
}
