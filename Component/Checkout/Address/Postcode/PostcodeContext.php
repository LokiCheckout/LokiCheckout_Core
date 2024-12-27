<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Component\Checkout\Address\Postcode;

use Magento\Directory\Helper\Data as DirectoryData;
use Yireo\LokiCheckout\Component\Base\AddressField\AddressFieldContext;

class PostcodeContext extends AddressFieldContext
{
    public function getDirectoryData(): DirectoryData
    {
        return $this->get(DirectoryData::class);
    }
}
