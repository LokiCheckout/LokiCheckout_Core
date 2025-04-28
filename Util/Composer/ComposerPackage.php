<?php declare(strict_types=1);

namespace Yireo\LokiCheckout\Util\Composer;

class ComposerPackage
{
    public function __construct(
        private readonly string $moduleName,
        private readonly string $composerName,
        private readonly string $composerVersion,
        private readonly string $composerDate,
    ) {
    }

    public function getModuleName(): string
    {
        return $this->moduleName;
    }

    public function getComposerName(): string
    {
        return $this->composerName;
    }

    public function getComposerVersion(): string
    {
        return $this->composerVersion;
    }

    public function getComposerDate(): string
    {
        return $this->composerDate;
    }
}
