<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Config\XmlConfig;

class FileReader extends \Magento\Framework\Config\Reader\Filesystem
{
    public function __construct(
        \Magento\Framework\Config\FileResolverInterface $fileResolver,
        \Yireo\LokiCheckout\Config\XmlConfig\Converter $converter,
        \Yireo\LokiCheckout\Config\XmlConfig\SchemaLocator $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
        $fileName = 'loki_checkout.xml',
        $idAttributes = [
            '/config/grid_layout/name' => 'id',
            '/config/grid_layout/field/name' => 'id',
        ],
        $domDocumentClass = \Magento\Framework\Config\Dom::class,
        $defaultScope = 'global'
    ) {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
    }
}
