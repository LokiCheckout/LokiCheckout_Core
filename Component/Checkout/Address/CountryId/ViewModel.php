<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\CountryId;

use Yireo\LokiCheckout\Component\Form\Field\AddressFieldViewModelViewModel;
use Yireo\LokiCheckout\Component\Form\Field\FieldBehaviour\OptionsBehaviour;

class ViewModel extends AddressFieldViewModelViewModel
{
    use OptionsBehaviour;

    protected array $filters = ['country_id'];
}
