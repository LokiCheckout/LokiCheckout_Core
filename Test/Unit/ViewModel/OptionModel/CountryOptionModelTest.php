<?php declare(strict_types=1);

namespace LokiCheckout\Core\Test\Unit\ViewModel\OptionModel;

use Loki\Components\Component\ComponentViewModelInterface;
use Loki\Field\Util\Option;
use Loki\Field\Util\OptionFactory;
use Loki\Field\Util\OptionGroup;
use Loki\Field\Util\OptionGroupFactory;
use LokiCheckout\Core\ViewModel\OptionModel\CountryOptionModel;
use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\ResourceModel\Country\Collection as CountryCollection;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
use Magento\Framework\DataObject;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CountryOptionModelTest extends TestCase
{
    private const COUNTRIES_PER_STORE = [
        1 => ['NL' => 'Netherlands', 'BE' => 'Belgium', 'AN' => 'Netherlands Antilles'],
        2 => ['DE' => 'Germany', 'AT' => 'Austria'],
    ];

    private int $storeId = 1;
    private int $collectionCreateCount = 0;
    private array $topCountryCodes = [];

    public function testSkipCountriesIsNotSharedBetweenInstances(): void
    {
        $withSkip = $this->createModel(['AN']);
        $withoutSkip = $this->createModel();

        $this->assertSame(['BE', 'NL'], $this->getValues($withSkip));
        $this->assertSame(['BE', 'NL', 'AN'], $this->getValues($withoutSkip));
    }

    public function testOptionsAreScopedPerStore(): void
    {
        $model = $this->createModel();

        $this->storeId = 1;
        $this->assertSame(['BE', 'NL', 'AN'], $this->getValues($model));

        $this->storeId = 2;
        $this->assertSame(['AT', 'DE'], $this->getValues($model));

        $this->storeId = 1;
        $this->assertSame(['BE', 'NL', 'AN'], $this->getValues($model));
        $this->assertSame(2, $this->collectionCreateCount);
    }

    public function testOptionsAreCachedForSameStore(): void
    {
        $model = $this->createModel();
        $viewModel = $this->createMock(ComponentViewModelInterface::class);

        $this->assertSame($model->getOptions($viewModel), $model->getOptions($viewModel));
        $this->assertSame(1, $this->collectionCreateCount);
    }

    public function testTopCountriesAreGrouped(): void
    {
        $this->topCountryCodes = ['NL'];
        $options = $this->createModel()->getOptions($this->createMock(ComponentViewModelInterface::class));

        $this->assertCount(2, $options);
        $this->assertContainsOnlyInstancesOf(OptionGroup::class, $options);
        $this->assertSame('Top Countries', $options[0]->getLabel());
        $this->assertSame(['NL'], $this->extractValues($options[0]->getOptions()));
        $this->assertSame('Other Countries', $options[1]->getLabel());
        $this->assertSame(['BE', 'AN'], $this->extractValues($options[1]->getOptions()));
    }

    private function createModel(array $skipCountries = []): CountryOptionModel
    {
        $directoryHelper = $this->createMock(DirectoryHelper::class);
        $directoryHelper->method('getTopCountryCodes')->willReturnCallback(fn () => $this->topCountryCodes);

        $optionFactory = $this->createMock(OptionFactory::class);
        $optionFactory->method('create')->willReturnCallback(
            fn ($value, $label) => new Option($value, $label)
        );

        $optionGroupFactory = $this->createMock(OptionGroupFactory::class);
        $optionGroupFactory->method('create')->willReturnCallback(
            fn ($label, $options) => new OptionGroup($label, $options)
        );

        $store = $this->createMock(StoreInterface::class);
        $store->method('getId')->willReturnCallback(fn () => $this->storeId);
        $storeManager = $this->createMock(StoreManagerInterface::class);
        $storeManager->method('getStore')->willReturn($store);

        return new CountryOptionModel(
            $directoryHelper,
            $optionGroupFactory,
            $optionFactory,
            $this->createCountryCollectionFactory(),
            $storeManager,
            $skipCountries
        );
    }

    private function createCountryCollectionFactory(): CountryCollectionFactory&MockObject
    {
        $factory = $this->createMock(CountryCollectionFactory::class);
        $factory->method('create')->willReturnCallback(function () {
            $this->collectionCreateCount++;
            $collection = $this->createMock(CountryCollection::class);
            $collection->method('loadByStore')->willReturnCallback(function ($storeId) use ($collection) {
                $countries = [];
                foreach (self::COUNTRIES_PER_STORE[$storeId] as $countryId => $name) {
                    $countries[] = new DataObject(['country_id' => $countryId, 'name' => $name]);
                }

                $collection->method('getIterator')->willReturn(new \ArrayIterator($countries));
                return $collection;
            });

            return $collection;
        });

        return $factory;
    }

    private function getValues(CountryOptionModel $model): array
    {
        return $this->extractValues($model->getOptions($this->createMock(ComponentViewModelInterface::class)));
    }

    private function extractValues(array $options): array
    {
        return array_values(array_map(fn (Option $option) => $option->getValue(), $options));
    }
}
