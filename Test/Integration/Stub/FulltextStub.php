<?php
declare(strict_types=1);

namespace Yireo\LokiCheckout\Test\Integration\Stub;

use Magento\CatalogSearch\Model\Indexer\Fulltext;

class FulltextStub extends Fulltext
{
    public function execute($entityIds)
    {
    }

    public function executeFull()
    {
    }

    public function executeRow($id)
    {
    }
}
